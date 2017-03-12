<?php

namespace Tekstove\ApiBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Tekstove\ApiBundle\Model\User;

/**
 * Description of UserVoter
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class UserVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof User) {
            return false;
        }
            
        return true;
    }

    /**
     * @param string $attribute
     * @param User $userToEdit
     * @param TokenInterface $token
     * @return bool|null
     */
    protected function voteOnAttribute($attribute, $userToEdit, TokenInterface $token)
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return null;
        }

        switch ($attribute) {
            case 'avatar':
            case 'about':
                if ($currentUser->getId() == $userToEdit->getId()) {
                    return true;
                }
        }
    }
}
