<?php

namespace Tekstove\ApiBundle\EventListener\Model\Chat;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Tekstove\ApiBundle\EventDispatcher\Chat\MessageEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;

use Potaka\BbcodeBundle\BbCode\TextToHtml;

/**
 * MessageHtmlSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageHtmlSubscriber implements EventSubscriberInterface
{

    /**
     * @var TextToHtml
     */
    private $bbCodeToHtml;

    public function __construct(TextToHtml $textToHtml)
    {
        $this->bbCodeToHtml = $textToHtml;
    }


    public static function getSubscribedEvents()
    {
        return array(
            'tekstove.chat.message.save' => 'saveEvent',
        );
    }

    public function saveEvent(Event $event)
    {
        if (!$event instanceof MessageEvent) {
            return false;
        }
        $message = $event->getMessage();
        $escapedMessage = htmlspecialchars($message->getMessage(), ENT_QUOTES);
        $html = $this->bbCodeToHtml->getHtml($escapedMessage);
        $message->setMessageHtml($html);
    }
}
