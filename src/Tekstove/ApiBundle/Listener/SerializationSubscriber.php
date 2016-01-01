<?php

namespace Tekstove\ApiBundle\Listener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;

/**
 * Description of SerializationListener
 *
 * @author potaka
 */
class SerializationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'class' => \Tekstove\ApiBundle\Model\Lyric::class,
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
        ];
    }
    
    public function onPostSerialize(ObjectEvent $event)
    {
        $data = $event->getObject();
        $visitor = $event->getVisitor();
        $context = $event->getContext();
        
        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass(\Tekstove\ApiBundle\Model\Lyric::class)
                                            ->propertyMetadata;
        
        $aclMetaData = $propertyMetadata['_acl'];
        
        $exclusionStrategy = $context->getExclusionStrategy();
        
        // @TODO use voters to get acl data
        
        if ($exclusionStrategy->shouldSkipProperty($aclMetaData, $context)) {
            $visitor->addData('_acl', 'false');
        } else {
            $visitor->addData('_acl', 'yeessss');
        }
    }
}