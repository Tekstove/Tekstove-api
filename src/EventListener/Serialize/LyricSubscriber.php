<?php

namespace App\EventListener\Serialize;

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

        if (!$lyric instanceof Lyric && !$lyric instanceof \App\Entity\Lyric\Lyric) {
            return false;
        }

        $visitor = $event->getVisitor();

        $extraInfoHtml = '';
        $extraInfo = $lyric->getextraInfo();
        if ($extraInfo) {
            $extraInfoHtml = $this->bbCode->getHtml($extraInfo);
        }

        $visitor->setData('extraInfoHtml', $extraInfoHtml);

        if ($lyric instanceof \App\Entity\Lyric\Lyric) {
            if ($lyric->isForbidden()) {
                $visitor->setData('forbidden', true);
            } else {
                $visitor->setData('forbidden', false);
            }
        }
    }
}
