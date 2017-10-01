<?php

namespace Tekstove\ApiBundle\Controller\Chat\Message;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;

class BanController extends Controller
{
    public function postAction(Request $request)
    {
        if (!$this->getUser()) {
            $accessDenied = $this->createAccessDeniedException('Not logged');
            throw $accessDenied;
        }

        $messageId = (int)$request->get('id');
        $minutes = (int)$request->get('minutes');

        $allowedMinutes = $this->getUser()->getPermission(Permission::CHAT_MESSAGE_BAN);

        if ($minutes > $allowedMinutes) {
            $error = new \Tekstove\ApiBundle\Exception\HumanReadableException();
            $error->addError('minutes', 'max minutes exceeded, max allowed: ' . $allowedMinutes);
            $view = $this->handleData(
                $request,
                $error->getErrors()
            );
            $view->setStatusCode(400);
            return $view;
        }

        $messageQuery = new MessageQuery();
        $message = $messageQuery->findOneById($messageId);

        $authorizationChecker = $this->get('security.authorization_checker');
        if (!$authorizationChecker->isGranted('ban', $message)) {
            $accessDenied = $this->createAccessDeniedException();
            throw $accessDenied;
        }

        $banSystem = $this->get('tekstove.api.security.ban_system');
        /* @var $banSystem \Tekstove\ApiBundle\Security\BanSystem */

        $banMinutesInSeconds = $minutes * 60;

        $banSystem->banIp(
            $request->getClientIp(),
            $banMinutesInSeconds,
            'chat ban'
        );

        $censoredMessage = new Message();
        $censoredMessage->setMessage(
            'banned by ' . $this->getUser()->getUsername() . PHP_EOL . $minutes . ' минути'
        );
        $censoredMessage->setIdOverride($message->getId());
        $censoredMessage->setUser($this->getUser());

        $messageRepository = $this->get('tekstove.chat.message.repository');
        $messageRepository->save($censoredMessage);

        return $this->handleData($request, []);
    }
}
