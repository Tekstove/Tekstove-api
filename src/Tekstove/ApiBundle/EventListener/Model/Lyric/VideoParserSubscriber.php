<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

use Tekstove\ApiBundle\Model\Map\LyricTableMap;
use Tekstove\UrlVideoParser\Youtube\YoutubeParser;
use Tekstove\UrlVideoParser\Exception\ParseException;
use Tekstove\UrlVideoParser\Vbox\VboxParser;

/**
 * VideoParserSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class VideoParserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'tekstove.lyric.save' => 'saveEvent',
        ];
    }
    
    public function saveEvent(Event $event)
    {
        if (!$event instanceof \Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent) {
            return false;
        }
        $lyric = $event->getLyric();
        
        if ($lyric->isModified(LyricTableMap::COL_VIDEO_YOUTUBE)) {
            try {
                $youtubeParser = new YoutubeParser();
                $lyric->setvideoYoutube($youtubeParser->getId($lyric->getvideoYoutube()));
            } catch (ParseException $e) {
                // do nothing
            }
        }
        
        if ($lyric->isModified(LyricTableMap::COL_VIDEO_VBOX7)) {
            try {
                $vboxParser = new VboxParser();
                $lyric->setvideoVbox7($vboxParser->getId($lyric->getvideoVbox7()));
            } catch (ParseException $e) {
                // do nothing
            }
        }
        
        return true;
    }
}
