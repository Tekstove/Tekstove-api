<?php

namespace App\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\Acl\Permission;

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
        if ($subject instanceof Lyric) {
            return true;
        }

        if ($subject instanceof \App\Entity\Lyric\Lyric) {
            return true;
        }
        
        return false;
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
                $sendById = -1;
                if ($lyric instanceof Lyric) {
                    $sendById = $lyric->getsendBy();
                } elseif ($lyric instanceof \App\Entity\Lyric\Lyric) {
                    $lyricUser = $lyric->getSendBy();
                    if ($lyricUser) {
                        $sendById = $lyricUser->getId();
                    }
                }


                if ($user->getId() == $sendById) {
                    return true;
                }
                
                // @TODO find better permission check. E.G.: lyric.*
                if ($user->getPermission(Permission::LYRIC_EDIT_DOWNLOAD)) {
                    return true;
                }
                
                if ($user->getPermission(Permission::LYRIC_EDIT_VIDEO)) {
                    return true;
                }
        }
    }
}
