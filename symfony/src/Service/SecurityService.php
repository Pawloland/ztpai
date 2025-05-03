<?php

namespace App\Service;

use App\Enum\CookieVariant;
use App\Enum\SessionVariant;
use App\Repository\BaseSessions\BaseSessionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityService
{
    private const int DAYS = 0;
    private const int HOURS = 0;
    private const int MINUTES = 90;


    public static function createAuthCookie(string $identifier_value, CookieVariant $variant, Request $request, Response &$response, EntityManagerInterface $em): void
    {
        $HTTPONLY = $variant->HTTPONLY();
        $NOTHTTPONLY = $variant->NOTHTTPONLY();
        $NOTHTTPONLY_IDENTIFIER = $variant->NOTHTTPONLY_IDENTIFIER();

        $sessionVariant = match ($variant) {
            CookieVariant::CLIENT => SessionVariant::CLIENT,
            CookieVariant::WORKER => SessionVariant::WORKER,
        };


        /** @var BaseSessionsRepository $repository */
        $repository = $em->getRepository($sessionVariant->ENTITY());

        $expiration_date = '';
        $token = $repository->createSession($sessionVariant, $identifier_value, static::DAYS, static::HOURS, static::MINUTES, $expiration_date);

        $cookieValueHTTPOnly = json_encode(['token' => $token]);
        $cookieValue = json_encode([$NOTHTTPONLY_IDENTIFIER => $identifier_value]);

        $response->headers->setCookie(
            Cookie::create($HTTPONLY)
                ->withValue($cookieValueHTTPOnly)
                ->withExpires($expiration_date)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite('Strict')
        );

        $response->headers->setCookie(
            Cookie::create($NOTHTTPONLY)
                ->withValue($cookieValue)
                ->withExpires($expiration_date)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(false)
                ->withSameSite('Strict')
        );

        // Optional: update for current execution scope
        $_COOKIE[$HTTPONLY] = $cookieValueHTTPOnly;
        $_COOKIE[$NOTHTTPONLY] = $cookieValue;
    }

    public static function updateAuthCookie(CookieVariant $variant, Request $request, Response &$response, EntityManagerInterface $em): bool
    {
        $HTTPONLY = $variant->HTTPONLY();
        $NOTHTTPONLY = $variant->NOTHTTPONLY();
        $NOTHTTPONLY_IDENTIFIER = $variant->NOTHTTPONLY_IDENTIFIER();

        $auth_httponly_raw = $request->cookies->get($HTTPONLY);
        $auth_raw = $request->cookies->get($NOTHTTPONLY);

        if (!$auth_httponly_raw || !$auth_raw
            || !($auth_httponly = json_decode($auth_httponly_raw, true))
            || !($auth = json_decode($auth_raw, true))
            || !isset($auth_httponly['token'])
            || !isset($auth[$NOTHTTPONLY_IDENTIFIER])) {
            return false;
        }

        $expiration_date = '';

        try {
            $sessionVariant = match ($variant) {
                CookieVariant::CLIENT => SessionVariant::CLIENT,
                CookieVariant::WORKER => SessionVariant::WORKER,
            };

            /** @var BaseSessionsRepository $repository */
            $repository = $em->getRepository($sessionVariant->ENTITY());

            $is_valid = $repository->checkSession(
                $sessionVariant,
                $auth[$NOTHTTPONLY_IDENTIFIER],
                $auth_httponly['token'],
                static::DAYS,
                static::HOURS,
                static::MINUTES,
                $expiration_date
            );
        } catch (Exception $e) {
            $is_valid = false;
        }

        $expires = $is_valid ? strtotime($expiration_date . ' UTC') : 1;

        $cookieValueHTTPOnly = json_encode(['token' => $auth_httponly['token']]);
        $cookieValue = json_encode([$NOTHTTPONLY_IDENTIFIER => $auth[$NOTHTTPONLY_IDENTIFIER]]);

        $response->headers->setCookie(
            Cookie::create($HTTPONLY)
                ->withValue($cookieValueHTTPOnly)
                ->withExpires($expires)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite('Strict')
        );

        $response->headers->setCookie(
            Cookie::create($NOTHTTPONLY)
                ->withValue($cookieValue)
                ->withExpires($expires)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(false)
                ->withSameSite('Strict')
        );

        // Optional: update for current execution scope
        $_COOKIE[$HTTPONLY] = $cookieValueHTTPOnly;
        $_COOKIE[$NOTHTTPONLY] = $cookieValue;

        return $is_valid;
    }


    public static function destroyAuthCookie(CookieVariant $variant, Request $request, Response &$response, EntityManagerInterface $em): void
    {
        $HTTPONLY = $variant->HTTPONLY();
        $NOTHTTPONLY = $variant->NOTHTTPONLY();
        $NOTHTTPONLY_IDENTIFIER = $variant->NOTHTTPONLY_IDENTIFIER();

        $auth_httponly_raw = $request->cookies->get($HTTPONLY);
        $auth_raw = $request->cookies->get($NOTHTTPONLY);

        if (!$auth_httponly_raw || !$auth_raw
            || !($auth_httponly = json_decode($auth_httponly_raw, true))
            || !($auth = json_decode($auth_raw, true))
            || !isset($auth_httponly['token'])
            || !isset($auth[$NOTHTTPONLY_IDENTIFIER])) {
            return;
        }

        $sessionVariant = match ($variant) {
            CookieVariant::CLIENT => SessionVariant::CLIENT,
            CookieVariant::WORKER => SessionVariant::WORKER,
        };

        /** @var BaseSessionsRepository $repository */
        $repository = $em->getRepository($sessionVariant->ENTITY());
        $repository->deleteSession($sessionVariant, $auth[$NOTHTTPONLY_IDENTIFIER], $auth_httponly['token']);

        $response->headers->setCookie(
            Cookie::create($HTTPONLY)
                ->withValue($auth_httponly_raw)
                ->withExpires(1)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite('Strict')
        );

        $response->headers->setCookie(
            Cookie::create($NOTHTTPONLY)
                ->withValue($auth_raw)
                ->withExpires(1)
                ->withPath('/')
                ->withSecure(true)
                ->withHttpOnly(false)
                ->withSameSite('Strict')
        );

        // Force delete from $_COOKIE for current execution scope
        unset($_COOKIE[$HTTPONLY]);
        unset($_COOKIE[$NOTHTTPONLY]);
    }


    public function checkPermission(string $perm_name): bool
    {
        //Assume authentication is already checked and the auth_admin cookie is set
//        $auth_admin = json_decode($_COOKIE[static::cookie_name_admin], true);
//        $nick = $auth_admin['nick'];
//        return $this->userRepository->checkPermission($nick, $perm_name);
        #TODO: Implement permission check
        return true;
    }

}