<?php

namespace Tekstove\ApiBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Tekstove\ApiBundle\Model\Artist;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tekstove\ApiBundle\Model\User;

/**
 * Description of ArtistVoter
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class ArtistVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Artist) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Artist $artist
     * @param TokenInterface $token
     * @return bool|null
     */
    protected function voteOnAttribute($attribute, $artist, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        switch ($attribute) {
            case 'edit':
                if ($user->getId() === 54) {
                    // @FIXME
                    return true;
                }

                return false;
        }
    }
}
