<?php

namespace App\EventListener\Model\Chat;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tekstove\ApiBundle\EventDispatcher\Chat\MessageEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;
use Tekstove\ApiBundle\Model\Chat\Message;

class ChatCommandSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tekstove.chat.message.save.post' => 'saveEvent',
        ];
    }

    public function saveEvent(Event $event)
    {
        if (!$event instanceof MessageEvent) {
            return false;
        }

        $message = $event->getMessage();
        if ($message instanceof \App\Entity\Chat\Message) {
            $messageText = $message->getMessage();
        } elseif ($message instanceof Message) {
            $messageText = $message->getMessage();
        } else {
            return false;
        }

        if ($messageText[0] === '!') {
            $commandMessage = new \App\Entity\Chat\Message();
            $commandMessage->setUsername('tekstove.info');

            $command = substr($messageText, 1);
            if (strpos($command, 'seen ') === 0) {
                $usernameToFind = substr($command, 5);
                $userRepo = $this->em->getRepository(User::class);
                $userToFind = $userRepo->findOneBy(['username' => $usernameToFind]);
                if (!$userToFind) {
                    $commandMessage->setMessage('Не намирам потребителя');
                } else {
                    $messageRepo = $this->em->getRepository(\App\Entity\Chat\Message::class);
                    $messageQueryBuilder = $messageRepo->createQueryBuilder('m');
                    /* @var $messageQueryBuilder QueryBuilder */
                    $messageQueryBuilder->setMaxResults(1);
                    $messageQueryBuilder->andWhere(
                        $messageQueryBuilder->expr()->eq('m.user', $userToFind->getId())
                    );

                    $messageQueryBuilder->addOrderBy('m.id', 'DESC');
                    $messageQuery = $messageQueryBuilder->getQuery();

                    $lastMessage = $messageQuery->getOneOrNullResult();
                    if ($lastMessage) {
                        $commandMessageText = "Последно съобщение на ";
                        $commandMessageText .= $lastMessage->getDate()->format('Y-m-d H:i:s');

                        $commandMessageText .= PHP_EOL;
                        $commandMessageText .= $lastMessage->getMessage();

                        $commandMessage->setMessage($commandMessageText);
                    } else {
                        $commandMessage->setMessage("Не намирам последно съобщение :(");
                    }
                }

                $this->em->persist($commandMessage);
                $this->em->flush();
            }
        }
    }
}
