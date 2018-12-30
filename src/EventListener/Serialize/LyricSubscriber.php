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

        $textError = ">>> грешка" . PHP_EOL;
        $textError .= "Нямаме права да ви покажем текства :(" . PHP_EOL;
        $textError .= "Ако сте собственик на текста, моля пишете ни на tekstove.info@gmail.com за съгласие";
        $textError .= "Собственици на текса са музикалната компания издала песента, изпълнителите и текстописецът.";
        $textError .= "Без разрешение от тях, нямаме право да покажем текста!";

        if ($lyric instanceof \App\Entity\Lyric\Lyric) {
            if ($lyric->isForbidden()) {
                $visitor->setData('text', $textError);
            }
        } elseif ($lyric instanceof Lyric) {
            $allowedLyrivs = [
                68126, // official fb page https://www.facebook.com/venelinstefanow/ on 1 Dec 2018
            ];

            if (in_array($lyric->getId(), $allowedLyrivs)) {
                return true;
            }

            $visitor->setData('text', $textError);
        }
    }
}
