<?php

namespace Tekstove\ApiBundle\EventListener\Model\Forum;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tekstove\ApiBundle\EventDispatcher\Forum\Post\PostEvent;
use Potaka\BbcodeBundle\BbCode\TextToHtmlInterface;
use Tekstove\ApiBundle\EventDispatcher\Event;

class PostHtmlSubscriber implements EventSubscriberInterface
{
    /**
     * @var TextToHtmlInterface
     */
    private $bbCodeToHtml;

    public function __construct(TextToHtmlInterface $textToHtml)
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
        $html = $this->bbCodeToHtml->getHtml($post->getText());

        $post->setTextHtml($html);
    }
}
