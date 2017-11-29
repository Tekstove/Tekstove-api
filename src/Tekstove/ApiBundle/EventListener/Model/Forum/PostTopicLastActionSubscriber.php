<?php

namespace Tekstove\ApiBundle\EventListener\Model\Forum;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tekstove\ApiBundle\EventDispatcher\Forum\Post\PostEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;
use Tekstove\ApiBundle\Model\Forum\TopicQuery;

class PostTopicLastActionSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tekstove.forum.post.save.completed' => 'saveEvent',
        ];
    }

    public function saveEvent(Event $event)
    {
        if (!$event instanceof PostEvent) {
            return false;
        }

        $topic = $event->getPost()->getTopic();

        $topic->setLastActivity(
            $topic->getLatestPost()->getDate()
        );

        // this do not work always !?
        $this->container->get('tekstove.forum.topic.repository')->save($topic);
    }
}
