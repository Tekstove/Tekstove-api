<?php

namespace App\EventListener\Model\Chat;

use App\Entity\Chat\Message;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Potaka\BbcodeBundle\BbCode\TextToHtmlInterface;

class ChatHtmlSubscriber implements EventSubscriber
{
    /**
     * @var TextToHtmlInterface
     */
    private $bbCodeToHtml;

    public function __construct(TextToHtmlInterface $textToHtml)
    {
        $this->bbCodeToHtml = $textToHtml;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $message = $event->getEntity();
        if (!$message instanceof Message) {
            return false;
        }

        $html = $this->bbCodeToHtml->getHtml($message->getMessage());
        $message->setMessageHtml($html);
    }
}
