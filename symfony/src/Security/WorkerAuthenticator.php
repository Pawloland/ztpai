<?php

namespace App\Security;

use App\Enum\CookieVariant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
class WorkerAuthenticator extends UserTypeAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $this->supportsType($request, CookieVariant::WORKER);

    }

    public function authenticate(Request $request): Passport
    {
        return $this->authenticateType($request, CookieVariant::WORKER);

    }


    final public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->onAuthenticationSuccessType($request, $token, $firewallName, CookieVariant::WORKER);
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
