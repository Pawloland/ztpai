<?php


namespace App\Security\Voter;

use App\Enum\Permissions;
use App\Enum\UserTypes;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermissionMyVoter extends Voter
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
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;

    }
}