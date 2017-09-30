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

        $banRedis = $this->get('tekstove.api.ban.redis');
        /* @var $banRedis \Predis\Client */
        $ipBanExists = $banRedis->get($request->getClientIp());

        // if ban exists -> do not overwrite
        if (!$ipBanExists) {
            $banRedis->setEx($request->getClientIp(), 15, 'chat ban');
        }


        $censoredMessage = new Message();
        $censoredMessage->setMessage('banned by ' . $this->getUser()->getUsername());
        $censoredMessage->setIdOverride($message->getId());
        $censoredMessage->setUser($this->getUser());

        $messageRepository = $this->get('tekstove.chat.message.repository');
        $messageRepository->save($censoredMessage);

        return $this->handleData($request, []);
    }
}
