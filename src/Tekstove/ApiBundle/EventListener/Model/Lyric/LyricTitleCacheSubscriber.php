<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\EventDispatcher\Event;

/**
 * Description of LyricSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricTitleCacheSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'tekstove.lyric.save' => 'saveEvent',
        );
    }
    
    public function saveEvent(Event $event)
    {
        if (!$event instanceof \Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent) {
            return false;
        }
        $lyric = $event->getLyric();
        $this->updateCache($lyric);
    
    }

    public function updateCache(Lyric $lyric)
    {
        $cacheTitleShort = '';
        
        $artists = $lyric->getOrderedArtists();
        
        array_slice($artists, 0, 2);
        if (isset($artists[0])) {
            $cacheTitleShort .= $artists[0]['name'];
        }
        
        if (isset($artists[1])) {
            // @TODO translate!
            $cacheTitleShort .= ' и ' . $artists[1]['name'];
        }
        
        if ($cacheTitleShort) {
            $cacheTitleShort .= ' - ';
        }
        $cacheTitleShort .= $lyric->getTitle();
        
        $lyric->setcacheTitleShort($cacheTitleShort);
    }
}
