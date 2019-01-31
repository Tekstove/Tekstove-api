<?php

namespace App\Controller\Chat;

use App\Controller\TekstoveAbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Tests\Request\ParamConverter\TestUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;
use Tekstove\ApiBundle\Model\Chat\Exception\MessageHumanReadableException;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\UserQuery;

/**
 * MessagesController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessagesController extends TekstoveAbstractController
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $messageQuery = new MessageQuery();

        if (empty($request->get('filter'))) {
            $maxIdQuery = new MessageQuery();
            $maxIdQuery->orderById(Criteria::DESC);
            $lastmessage = $maxIdQuery->findOne();
            $messageQuery->filterById(
                $lastmessage->getId() - 20,
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

            if ($message->getMessage()[0] === '!') {
                $commandMessage = new Message();
                $commandMessage->setUsername('tekstove.info');

                $command = substr($message->getMessage(), 1);
                if (strpos($command, 'seen ') === 0) {
                    $usernameToFind = substr($command, 5);
                    $userRepo = new UserQuery();
                    $userToFind = $userRepo->findOneByUsername($usernameToFind);
                    if (!$userToFind) {
                        $commandMessage->setMessage('Не намирам потребителя');
                    } else {
                        $messageRepo = new MessageQuery();
                        $messageRepo->addDescendingOrderByColumn('id');
                        $messageRepo->filterByUserId($userToFind->getId());
                        $messageRepo->limit(1);
                        $lastMessage = $messageRepo->findOne();
                        if ($lastMessage) {

                            $commandMessageText = "Последно съобщение на ";
                            $commandMessageText .= $lastMessage->getDate('Y-m-d H:i:s');

                            $commandMessageText .= PHP_EOL;
                            $commandMessageText .= $lastMessage->getMessage();

                            $commandMessage->setMessage($commandMessageText);
                        } else {
                            $commandMessage->setMessage("Не намирам последно съобщение :(");
                        }
                    }

                    $messageRepository->save($commandMessage);
                }

            }

        } catch (MessageHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
        return $this->handleData($request, $message);
    }
}
