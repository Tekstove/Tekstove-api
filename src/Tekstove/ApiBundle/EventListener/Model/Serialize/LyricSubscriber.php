<?php

namespace Tekstove\ApiBundle\EventListener\Model\Serialize;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Tekstove\ApiBundle\Model\Lyric;
use Potaka\BbcodeBundle\BbCode\TextToHtml;

/**
 * @author po_taka
 * 
 * This handle actions after serialization
 */
class LyricSubscriber implements EventSubscriberInterface 
{
    /**
     * @var TextToHtml
     */
    private $bbCode;
    
    public function __construct(TextToHtml $bbCode) {
        // I will create bbCode with cache and I should update the code below
        $this->bbCode = $bbCode;
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
        $lyric = $event->getObject();
        
        if (!$lyric instanceof Lyric) {
            return false;
        }
        
        $visitor = $event->getVisitor();
        
        $extraInfoHtml = '';
        $extraInfo = $lyric->getextraInfo();
        if ($extraInfo) {
            $escapedMessage = htmlspecialchars($extraInfo, ENT_QUOTES);
            $newLinedMessage = nl2br($escapedMessage);
            $extraInfoHtml = $this->bbCode->getHtml($newLinedMessage);
        }
        
        $visitor->addData('extraInfoHtml', $extraInfoHtml);
        
    }
}
