<?php

namespace Tekstove\ApiBundle\Security\Authorization\Voter\Chat;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Tekstove\ApiBundle\Model\Chat\Message;
use App\Entity\Chat\Message as MessageV4;

use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\Acl\Permission;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        if ($subject instanceof Message) {
            return true;
        }

        if ($subject instanceof MessageV4) {
            return true;
        }

        return false;
    }

    /**
     * @param string $attribute
     * @param Message $message
     * @param TokenInterface $token
     * @return bool|null
     */
    protected function voteOnAttribute($attribute, $message, TokenInterface $token)
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return null;
        }

        switch ($attribute) {
            case 'viewIp':
                if ($user->getPermission(Permission::CHAT_MESSAGE_VIEW_DETAILS)) {
                    return true;
                }
                return false;
            case 'censore':
                if ($user->getPermission(Permission::CHAT_MESSAGE_CENSORE)) {
                    return true;
                }

                return false;

            case 'ban':
                if ($user->getPermission(Permission::CHAT_MESSAGE_BAN)) {
                    return true;
                }

                return false;
        }
    }
}
