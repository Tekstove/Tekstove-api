<?php

namespace Tekstove\ApiBundle\EventListener\Model\Serialize;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Tekstove\ApiBundle\Model\Acl\AutoAclSerializableInterface;
use Tekstove\ApiBundle\Model\Acl\EditableInterface;

/**
 * AclSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class AclSubscriber implements EventSubscriberInterface
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
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $this->handleAclField($event);
        $this->getAllowedFields($event);
    }

    private function getAllowedFields(ObjectEvent $event)
    {
        $object = $event->getObject();

        if (false === $object instanceof EditableInterface) {
            return true;
        }

        $objectClass = get_class($object);

        $visitor = $event->getVisitor();
        $context = $event->getContext();

        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass($objectClass)
                                            ->propertyMetadata;

        switch (get_class($object)) {
            case \Tekstove\ApiBundle\Model\User::class:
                $properties = [
                    'avatar',
                    'about',
                ];
                break;
            default:
                return true;
        }

        $editableFieldsMetaData = $propertyMetadata['editableFields'];

        $exclusionStrategy = $context->getExclusionStrategy();

        // @TODO
        // really, I don't know why there is no exclusion strategy!
        if (!$exclusionStrategy) {
            return false;
        }

        if (false == $exclusionStrategy->shouldSkipProperty($editableFieldsMetaData, $context)) {
            $editableFields = [];
            foreach ($properties as $property) {
                if ($this->authorizationChecker->isGranted($property, $object)) {
                    $editableFields[$property] = $property;
                }
            }
            $visitor->addData('_editableFields', $editableFields);
        }
    }

    public function handleAclField(ObjectEvent $event)
    {
        $object = $event->getObject();
        
        if (false === $object instanceof AutoAclSerializableInterface) {
            return true;
        }
        
        $objectClass = get_class($object);
        
        $visitor = $event->getVisitor();
        $context = $event->getContext();
        
        $propertyMetadata = $context->getmetadataFactory()
                                        ->getMetadataForClass($objectClass)
                                            ->propertyMetadata;
        
        $aclMetaData = $propertyMetadata['acl'];

        $exclusionStrategy = $context->getExclusionStrategy();

        // @TODO
        // really, I don't know why there is no exclusion strategy!
        if (!$exclusionStrategy) {
            return false;
        }

        if (false == $exclusionStrategy->shouldSkipProperty($aclMetaData, $context)) {
            $acl = [];
            $permissions = ['edit'];
            foreach ($permissions as $permission) {
                if ($this->authorizationChecker->isGranted($permission, $object)) {
                    $acl[$permission] = 1;
                }
            }
            $visitor->addData('acl', $acl);
        }
    }
}
