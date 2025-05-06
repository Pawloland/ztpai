<?php

namespace App\Security;

use App\Enum\CookieVariant;
use App\Service\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
abstract class UserTypeAuthenticator extends AbstractAuthenticator
{
    private $response;


    public function __construct(
        private EntityManagerInterface $em
    )
    {
        $this->response = new JsonResponse();
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    final public function supportsType(Request $request, CookieVariant $cookieVariant): ?bool
    {
        return $request->cookies->has($cookieVariant->HTTPONLY()) &&
            $request->cookies->has($cookieVariant->NOTHTTPONLY());

    }

    final public function authenticateType(Request $request, CookieVariant $cookieVariant): Passport
    {
        $auth_ok = SecurityService::updateAuthCookie($cookieVariant, $request, $this->response, $this->em, $user);

        if (!$auth_ok) {
            throw new CustomUserMessageAuthenticationException("No valid cookies found");
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), fn() => $user));
        // $apiToken = $request->headers->get('X-AUTH-TOKEN');
        // if (null === $apiToken) {
        // The token header was empty, authentication fails with HTTP Status
        // Code 401 "Unauthorized"
        // throw new CustomUserMessageAuthenticationException('No API token provided');
        // }

        // implement your own logic to get the user identifier from `$apiToken`
        // e.g. by looking up a user in the database using its API key
        // $userIdentifier = /** ... */;

        // return new SelfValidatingPassport(new UserBadge($userIdentifier));
    }

    final public function onAuthenticationSuccessType(Request $request, TokenInterface $token, string $firewallName, CookieVariant $cookieVariant): ?Response
    {
        $request->attributes->set($cookieVariant->name, $this->response);

        return null;
    }

    final public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->response;
    }

    // public function start(Request $request, ?AuthenticationException $authException = null): Response
    // {
    //     /*
    //      * If you would like this class to control what happens when an anonymous user accesses a
    //      * protected page (e.g. redirect to /login), uncomment this method and make this class
    //      * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //      *
    //      * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //      */
    // }
}
