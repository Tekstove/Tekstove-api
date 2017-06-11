<?php

namespace Tekstove\ApiBundle\EventListener\Model\Serialize\Chat;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Tekstove\ApiBundle\Model\Chat\Message;

/**
 * Description of MessageSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageSubscriber implements EventSubscriberInterface
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authorizationChecker = $authChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $this->addCensor($event);

        $object = $event->getObject();

        if (false === $object instanceof Message) {
            return true;
        }

        $visitor = $event->getVisitor();
        $context = $event->getContext();

        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass(Message::class)
                                            ->propertyMetadata;

        $ipMetaData = $propertyMetadata['ip'];

        $exclusionStrategy = $context->getExclusionStrategy();

        if (false == $exclusionStrategy->shouldSkipProperty($ipMetaData, $context)) {
            if (false === $this->authorizationChecker->isGranted('viewIp', $object)) {
                $visitor->setdata('ip', null);
            }
        }
    }

    public function addCensor(ObjectEvent $event)
    {
        $object = $event->getObject();
        if (false === $object instanceof Message) {
            return true;
        }

        $visitor = $event->getVisitor();

        if ($this->authorizationChecker->isGranted('censore', $object)) {
            $visitor->setdata('_meta', ['censore' => true]);
        } else {
            $visitor->setdata('_meta', ['censore' => false]);
        }
    }
}
