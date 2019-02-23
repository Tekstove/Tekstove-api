<?php

namespace App\Controller\Chat;

use App\Controller\TekstoveAbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;
use Tekstove\ApiBundle\Model\Chat\Exception\MessageHumanReadableException;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessagesController extends TekstoveAbstractController
{
    /**
     * @deprecated
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $logger->error("Code is deprecated and will be removed!", ['class' => __CLASS__, 'method' => __METHOD__]);

        $this->applyGroups($request);
        $messageQuery = new MessageQuery();

        if (empty($request->get('filter'))) {
            $maxIdQuery = new MessageQuery();
            $maxIdQuery->filterByIdOverride(null);
            $maxIdQuery->orderById(Criteria::DESC);
            $maxIdQuery->offset(21);
            $lastmessage = $maxIdQuery->findOne();

            $startId = 0;
            if ($lastmessage) {
                $startId = $lastmessage->getId();
            }
            $messageQuery->filterById(
                $startId,
                Criteria::GREATER_THAN
            );
        }

        $messageQuery->orderById(Criteria::ASC);

        return $this->handleData($request, $messageQuery);
    }

    public function postAction(Request $request)
    {
        $this->getContext()
                ->setGroups(['List']);

        $message = new Message();
        $messageData = json_decode($request->getContent(), true);

        $message->setMessage($messageData['message']);
        $ips = $request->getClientIps();
        $message->setIp(implode('~', $ips));

        if ($this->getUser()) {
            $message->setUser($this->getUser());
            $message->setUsername($this->getUser()->getUsername());
        } else {
            // anonymous user
            $userName = (new \App\HttpFoundation\RequestIdentificator())->identify($request);
            $message->setUsername($userName);
        }

        $messageRepository = $this->get('tekstove.chat.message.repository');
        try {
            $messageRepository->save($message);
        } catch (MessageHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
        return $this->handleData($request, $message);
    }
}
