<?php

namespace App\Security;

use App\Enum\CookieVariant;
use App\Enum\Globals;
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

        // append the user entity to the request instead of relying only on the returned Password
        // then return the current user entity, but attach also the list of all entities set in the request
        // this way we can gather all the entities from every authenticator (assuming it is using the same convention as in this method)
        // so that later our custom voter can get all the resolved entities, but also know which was resolved as the last one
        // (returned by us in userLoader parameter when creating the UserBadge)
        // this way a user can perform actions allowed for clients and workers at the same time (provided appropriate cookies are set)
        // and doesn't have to first log out from Client and then login as Worker and vice versa to perform actions allowed for one another

        $authenticatedEntities = $request->attributes->get(Globals::AUTHENTICATED_ENTITIES, []);
        if (!is_array($authenticatedEntities)) {
            throw new CustomUserMessageAuthenticationException('Could not start authentication process correctly');
        }

        if (!array_key_exists($cookieVariant->name, $authenticatedEntities)) { // if there is no array for specific cookie variant yet
            $authenticatedEntities[$cookieVariant->name] = [];
        }

        if (array_all($authenticatedEntities[$cookieVariant->name], fn($element) => $element->getId() !== $user->getId())) { // if the user is not in it yet
            $authenticatedEntities[$cookieVariant->name][] = $user; // add the user to the array for this cookie variant
        }

        $request->attributes->set(Globals::AUTHENTICATED_ENTITIES, $authenticatedEntities);


        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), fn() => $user, $authenticatedEntities));
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
