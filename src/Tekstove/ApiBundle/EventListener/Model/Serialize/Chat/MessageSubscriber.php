<?php

namespace Tekstove\ApiBundle\EventListener\Model\Serialize\Chat;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Tekstove\ApiBundle\Model\Chat\Message;
use App\Entity\Chat\Message as MessageV4;

/**
 * Description of MessageSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageSubscriber implements EventSubscriberInterface
{
    private $authorizationChecker;

    private $metaData = [];

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
        $object = $event->getObject();

        if (false === $object instanceof Message) {
            if (!$object instanceof MessageV4) {
                return true;
            }
        }

        $this->addCensor($event);
        $this->addBan($event);

        $visitor = $event->getVisitor();
        $context = $event->getContext();

        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass(Message::class)
                                            ->propertyMetadata;

        $propertyMetadataV4 = $context->getmetadataFactory()
                                ->getMetadataForClass(MessageV4::class)
                                    ->propertyMetadata;

        $ipMetaDataV4 = $propertyMetadataV4['ip'];

        $ipMetaData = $propertyMetadata['ip'];

        $exclusionStrategy = $context->getExclusionStrategy();

        if (false == $exclusionStrategy->shouldSkipProperty($ipMetaData, $context) || false == $exclusionStrategy->shouldSkipProperty($ipMetaDataV4, $context)) {
            if (false === $this->authorizationChecker->isGranted('viewIp', $object)) {
                $visitor->setdata('ip', null);
            }
        }

        $visitor->addData('_meta', $this->metaData);
    }

    public function addBan(ObjectEvent $event)
    {
        $object = $event->getObject();

        if ($this->authorizationChecker->isGranted('ban', $object)) {
            $this->metaData['ban'] = true;
        } else {
            $this->metaData['ban'] = false;
        }
    }

    public function addCensor(ObjectEvent $event)
    {
        $object = $event->getObject();

        if ($this->authorizationChecker->isGranted('censore', $object)) {
            $this->metaData['censore'] = true;
        } else {
            $this->metaData['censore'] = false;
        }
    }
}
