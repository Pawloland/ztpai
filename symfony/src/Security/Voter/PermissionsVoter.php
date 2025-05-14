<?php


namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Worker;
use App\Enum\CookieVariant;
use App\Enum\Globals;
use App\Enum\Permissions;
use App\Enum\UserTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionsVoter extends Voter
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    private static function validateAttributeFormat(string $attribute, ?UserTypes &$userType = null, ?Permissions &$permission = null): bool
    {
        // Match a valid user type and an optional permission (with multiple underscores allowed)
        if (preg_match('/^([A-Za-z]+)(?:_([A-Za-z_]+))?$/', $attribute, $matches)) {
            $userType = $matches[1]; // The first part is always the user type (e.g., GUEST, CLIENT, WORKER)
            $permission = $matches[2] ?? null; // The second part is optional (the permission, if provided)

            $userType = UserTypes::tryFrom($userType);

            // Validate that the user type is correct using tryFrom
            if ($userType === null) {
                $userType = UserTypes::GUEST;
                return false;
            }

            if ($permission) {
                $permission = Permissions::tryFromName($permission);
                if ($permission === null) {
                    return false;
                }
                return true;

            }
            return true;
        }

        return false;
    }


    private static function setAuthenticatedUserInRequestAttribute(UserInterface $user, Request &$request): void
    {
        $request->attributes->set(Globals::AUTHORIZED_ENTITY, $user);
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::validateAttributeFormat($attribute);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        self::validateAttributeFormat($attribute, $userTypeFromAttribute, $permissionFromAttribute);

        // $attribute -- string that defines a permission
        // $subject -- entity which will be accessed/modified on HTTP request
        // $token -- includes method to retrieve User or Worker object based on auth cookies, or null if not authenticated

        // $userTypeFromAttribute - UserType parsed from attribute string, when can't map attribute to UserType, GUEST is returned
        // $permissionFromAttribute - Permission parsed from attribute string or null (because can be empty for Client or Guest)


        // get all resolved entities, which we manually saved in the request attributes, if any
        $currentRequest = $this->requestStack->getCurrentRequest();
        $authenticatedEntities = $currentRequest->get(Globals::AUTHENTICATED_ENTITIES, []);

        //only check if the attribute is array (if it isn't, then it was not set by us, or was overridden by something else,
        // which should be considered a bug)
        if (is_array($authenticatedEntities)) {

            //for each key name in array (those should be names of userTypes enum, but are set based on CookieVariant in authenticator,
            // which are the same, unless someone adds a new CookieVariant enum entry without also adding new corresponding UserTypes enum entry )
            foreach ($authenticatedEntities as $key => $entities) {
                if ($key !== CookieVariant::WORKER->name &&
                    $key !== CookieVariant::CLIENT->name) {
                    continue; // we only implement validation for CLIENT and WORKER explicitly
                }


                //for each entity in the array
                foreach ($entities as $entity) {
                    # GUEST HANDLING
                    if ($userTypeFromAttribute === UserTypes::GUEST) {
                        //those checks "instance of" are redundant, since we always pass the entity of the same type as the key name,
                        // but if the bug gets added elsewhere (like authenticator), then it will be caught here
                        //entities provided by the request attributes can be only instance of Client or Worker,
                        // not Guest, so here we check only for client, which is different from the check for a token, which also check for null
                        if ($entity instanceof Client) {
                            self::setAuthenticatedUserInRequestAttribute($entity, $currentRequest); // save the user deemed authorized in the request attributes, for custom state processors
                            return true; // if we found a client, then we can stop checking, by short-circuiting - early return,
                            // but only for true, for false we have to check all other entities first and then the entity from the token
                        }
                    }

                    # CLIENT HANDLING
                    if ($userTypeFromAttribute === UserTypes::CLIENT
                    ) {
                        if (($entity instanceof Client) && in_array($attribute, $entity->getRoles())) {
                            self::setAuthenticatedUserInRequestAttribute($entity, $currentRequest);
                            return true;
                        }
                    }

                    # WORKER HANDLING
                    if ($userTypeFromAttribute === UserTypes::WORKER) {
                        if (($entity instanceof Worker) && in_array($attribute, $entity->getRoles())) {
                            self::setAuthenticatedUserInRequestAttribute($entity, $currentRequest);
                            return true;
                        }
                    }
                }
            }
        }


        //AUTHORIZATION CHECK BASED ON tokenInterface method parameter

        // The user from token have been probably already checked by the code above, but there is no guarantee that the user from token must be in entities saved in
        // the request attributes, because something could erroneously delete or overwrite our custom request attribute or there could not be a request at all
        // (like in CLI commands), so we have to check the user from token as well, possibly twice.
        // We could check if the user from token was already checked in the request attributes, but checking it would require more memory + computation time,
        // so it is cheaper to just check user's authorization again than ensuring it wasn't checked before
        // The worst case could be that the same user was checked before in the request attributes and wasn't deemed authorized,
        // and we check it again bellow, which will again deem it not authorized, so it will be rejected again
        // If it was deemed authorized previously, then this point of the code will never be reached, because the above code uses short-circuiting,
        // early returns when user is deemed authorized.

        // $userFromAuth - User or Worker object based on auth cookies, or null if not authenticated
        $userFromAuth = $token->getUser();

        # GUEST HANDLING
        if ($userTypeFromAttribute === UserTypes::GUEST) {
            $isAuthorized = ($userFromAuth === null || $userFromAuth instanceof Client);
            if ($isAuthorized) {
                self::setAuthenticatedUserInRequestAttribute($userFromAuth, $currentRequest);
            }
            return $isAuthorized;
        }

        # CLIENT HANDLING
        if ($userTypeFromAttribute === UserTypes::CLIENT
        ) {
            $isAuthorized = ($userFromAuth instanceof Client) && in_array($attribute, $userFromAuth->getRoles());
            if ($isAuthorized) {
                self::setAuthenticatedUserInRequestAttribute($userFromAuth, $currentRequest);
            }
            return $isAuthorized;
        }

        # WORKER HANDLING
        if ($userTypeFromAttribute === UserTypes::WORKER) {
            $isAuthorized = ($userFromAuth instanceof Worker) && in_array($attribute, $userFromAuth->getRoles());
            if ($isAuthorized) {
                self::setAuthenticatedUserInRequestAttribute($userFromAuth, $currentRequest);
            }
            return $isAuthorized;
        }

        return false; // By default, reject everything.
        // This place should never be reached, every case must be handled above
        // This is just a safeguard if in the future the above validation is changed and some bug is introduced
    }
}