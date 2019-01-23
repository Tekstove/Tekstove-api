<?php

namespace App\EventListener\Serialize;

use App\Entity\Artist\Artist;
use Potaka\BbcodeBundle\BbCode\TextToHtmlInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class ArtistSubscriber implements EventSubscriberInterface
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
        $artist = $event->getObject();

        if ($artist instanceof Artist) {
            $info = $artist->getAbout();
        } elseif ($artist instanceof \Tekstove\ApiBundle\Model\Artist) {
            $info = $artist->getAbout();
        } else {
            return false;
        }

        $visitor = $event->getVisitor();

        $infoHtml = '';
        if ($info) {
            $infoHtml = $this->bbCode->getHtml($info);
        }

        $visitor->setData('aboutHtml', $infoHtml);
    }
}
