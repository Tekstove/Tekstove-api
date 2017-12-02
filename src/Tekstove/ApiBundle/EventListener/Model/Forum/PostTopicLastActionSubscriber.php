<?php

namespace Tekstove\ApiBundle\EventListener\Model\Forum;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tekstove\ApiBundle\EventDispatcher\Forum\Post\PostEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;

class PostTopicLastActionSubscriber implements EventSubscriberInterface
{
    private $container;

    /**
     * I know that injecting the container is bad.
     * We have circular reference here.
     * And there are bigger issues that have to be solved.
     *
     * @param $container
     */
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
        // @FIXME
        $this->container->get('tekstove.forum.topic.repository')->save($topic);
    }
}
