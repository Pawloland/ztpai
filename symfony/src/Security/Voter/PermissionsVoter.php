<?php


namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Worker;
use App\Enum\Permissions;
use App\Enum\UserTypes;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermissionsVoter extends Voter
{

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
                $permission = Permissions::tryFrom($permission);
                if ($permission === null) {
                    return false;
                }
                return true;

            }
            return true;
        }

        return false;
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

        // $userFromAuth - User or Worker object based on auth cookies, or null if not authenticated
        $userFromAuth = $token->getUser();

        # GUEST HANDLING
        if ($userTypeFromAttribute === UserTypes::GUEST) {
            return ($userFromAuth === null || $userFromAuth instanceof Client);
        }

        # CLIENT HANDLING
        if ($userTypeFromAttribute === UserTypes::CLIENT &&
            ($userFromAuth instanceof Client)
        ) {
            return in_array($attribute, $userFromAuth->getRoles());
        }

        # WORKER HANDLING
        if ($userTypeFromAttribute === UserTypes::WORKER &&
            ($userFromAuth instanceof Worker)
        ) {
            return in_array($attribute, $userFromAuth->getRoles());
        }

        return false; // By default, reject everything.
        // This place should never be reached, every case must be handled above
        // This is just a safeguard if in the future the above validation is changed and some bug is introduced
    }
}