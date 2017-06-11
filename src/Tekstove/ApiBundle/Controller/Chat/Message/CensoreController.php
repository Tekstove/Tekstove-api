<?php

namespace Tekstove\ApiBundle\Controller\Chat\Message;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;

/**
 * Description of Censore
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CensoreController extends Controller
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
        if (!$authorizationChecker->isGranted('censore', $message)) {
            $accessDenied = $this->createAccessDeniedException();
            throw $accessDenied;
        }

        $censoredMessage = new Message();
        $censoredMessage->setMessage('censored by ' . $this->getUser()->getUsername());
        $censoredMessage->setIdOverride($message->getId());
        $censoredMessage->setUser($this->getUser());

        $messageRepository = $this->get('tekstove.chat.message.repository');
        $messageRepository->save($censoredMessage);

        return $this->handleData($request, []);
    }
}
