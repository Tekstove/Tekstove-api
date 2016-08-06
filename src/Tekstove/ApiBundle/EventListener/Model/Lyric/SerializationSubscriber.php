<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Tekstove\ApiBundle\Model\Lyric;

/**
 * Description of SerializationListener
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class SerializationSubscriber implements EventSubscriberInterface
{
    private $authorizationChecker = null;
    
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authorizationChecker = $authChecker;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'class' => \Tekstove\ApiBundle\Model\Lyric::class,
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
            [
                'event' => 'serializer.pre_serialize',
                'class' => \Tekstove\ApiBundle\Model\Lyric::class,
                'method' => 'onPreSerialize',
                'format' => 'json',
            ]
        ];
    }
    
    public function onPostSerialize(ObjectEvent $event)
    {
        $lyric = $event->getObject();
        /* @lyric \Tekstove\ApiBundle\Model\Lyric */
        $visitor = $event->getVisitor();
        $context = $event->getContext();
        
        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass(\Tekstove\ApiBundle\Model\Lyric::class)
                                            ->propertyMetadata;
        
        $aclMetaData = $propertyMetadata['acl'];
        
        $exclusionStrategy = $context->getExclusionStrategy();
        
        // @TODO use voters to get acl data
        
        
        if (false == $exclusionStrategy->shouldSkipProperty($aclMetaData, $context)) {
            $acl = [];
            $permissions = ['edit'];
            foreach ($permissions as $permission) {
                if ($this->authorizationChecker->isGranted($permission, $lyric)) {
                    $acl[$permission] = 1;
                }
            }
            $visitor->addData('acl', $acl);
        }
        
        $this->clearForbiddenLyricsData($lyric);
    }
    
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $lyric = $event->getObject();
        /* @lyric \Tekstove\ApiBundle\Model\Lyric */
        $this->clearForbiddenLyricsData($lyric);
    }
    
    private function clearForbiddenLyricsData(Lyric $lyric)
    {
        $forbiddenArtist = $lyric->getForbidden();
        if ($forbiddenArtist) {
            $lyric->setText("Artist {$forbiddenArtist->getName()} is forbidden");
        }
    }
}
