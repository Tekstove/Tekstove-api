<?php

namespace Tekstove\ApiBundle\Controller\Chat\Message;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;

class BanController extends Controller
{
    public function postAction(Request $request)
    {
        if (!$this->getUser()) {
            $accessDenied = $this->createAccessDeniedException('Not logged');
            throw $accessDenied;
        }

        $messageId = (int)$request->get('id');

        $messageQuery = new MessageQuery();
        $message = $messageQuery->findOneById($messageId);

        $authorizationChecker = $this->get('security.authorization_checker');
        if (!$authorizationChecker->isGranted('ban', $message)) {
            $accessDenied = $this->createAccessDeniedException();
            throw $accessDenied;
        }

        $banSystem = $this->get('tekstove.api.security.ban_system');
        /* @var $banSystem \Tekstove\ApiBundle\Security\BanSystem */
        $banSystem->banIp($request->getClientIp(), 15 * 60, 'chat ban');
        // @FIXME hardcoded 15minutes

        $censoredMessage = new Message();
        $censoredMessage->setMessage('banned by ' . $this->getUser()->getUsername());
        $censoredMessage->setIdOverride($message->getId());
        $censoredMessage->setUser($this->getUser());

        $messageRepository = $this->get('tekstove.chat.message.repository');
        $messageRepository->save($censoredMessage);

        return $this->handleData($request, []);
    }
}
