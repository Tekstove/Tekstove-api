<?php

namespace Tekstove\ApiBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Tekstove\ApiBundle\Model\Lyric;

use Tekstove\ApiBundle\Model\User;

/**
 * Description of LyricVoter
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Lyric) {
            return false;
        }
        
        return true;
    }

    /**
     * @param string $attribute
     * @param Lyric $lyric
     * @param TokenInterface $token
     * @return bool|null
     */
    protected function voteOnAttribute($attribute, $lyric, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return null;
        }
        switch ($attribute) {
            case 'edit':
                if ($user->getId() == $lyric->getsendBy()) {
                    return true;
                }
        }
    }
}
