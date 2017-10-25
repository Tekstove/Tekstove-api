<?php

namespace Tekstove\ApiBundle\EventListener\Model\Forum;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tekstove\ApiBundle\EventDispatcher\Forum\Post\PostEvent;
use Potaka\BbcodeBundle\BbCode\TextToHtml;
use Tekstove\ApiBundle\EventDispatcher\Event;

class PostHtmlSubscriber implements EventSubscriberInterface
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
            'tekstove.forum.post.save' => 'saveEvent',
        );
    }

    public function saveEvent(Event $event)
    {
        if (!$event instanceof PostEvent) {
            return false;
        }

        $post = $event->getPost();
        $escapedMessage = htmlspecialchars($post->getText(), ENT_QUOTES);
        $newLinedMessage = nl2br($escapedMessage);
        $html = $this->bbCodeToHtml->getHtml($newLinedMessage);

        $post->setTextHtml($html);
    }
}
