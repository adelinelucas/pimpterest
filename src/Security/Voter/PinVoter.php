<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PinVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        // supports method nous dit si non voter doit être appliqué
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === 'PIN_CREATE' || (in_array($attribute, ['PIN_MANAGE'])
            && $subject instanceof \App\Entity\Pin);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // subject fait référence à notre pin
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PIN_CREATE':
                return $user->isVerified() ;
                break;
            case 'PIN_MANAGE':
                return $user->isVerified() && $user == $subject->getUser();
                break;
        }

        return false;
    }
}
