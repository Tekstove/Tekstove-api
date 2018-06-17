<?php

namespace Tekstove\ApiBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Tekstove\ApiBundle\Model\Album;
use Tekstove\ApiBundle\Model\Acl\Permission;

use Tekstove\ApiBundle\Model\User;

/**
 * Description of AlbumVoter
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class AlbumVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Album) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Album $album
     * @param TokenInterface $token
     * @return bool|null
     */
    protected function voteOnAttribute($attribute, $album, TokenInterface $token)
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return null;
        }

        switch ($attribute) {
            case 'edit':
                if ($user->getId() == $album->getSendBy()) {
                    return true;
                }

                if ($user->getPermission(Permission::ALBUM_EDIT)) {
                    return true;
                }
        }
    }
}
