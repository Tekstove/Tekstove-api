<?php

namespace Tekstove\ApiBundle\EventListener\Model\Serialize;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Tekstove\ApiBundle\Model\Lyric;
use Potaka\BbcodeBundle\BbCode\TextToHtmlInterface;

/**
 * @author po_taka
 *
 * This handle actions after serialization
 */
class LyricSubscriber implements EventSubscriberInterface
{
    /**
     * @var TextToHtmlInterface
     */
    private $bbCode;

    public function __construct(TextToHtmlInterface $bbCode)
    {
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
        
        $visitor->setData('extraInfoHtml', $extraInfoHtml);
        
    }
}
