<?php

namespace Tekstove\ApiBundle\Model\Lyric;

use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\EventDispatcher\EventDispacher;

/**
 * Description of LyricRepository
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricRepository
{
    private $eventDispacher;
    
    public function __construct(EventDispacher $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }
    
    public function save(Lyric $lyric)
    {
        $lyric->setEventDispacher($this->eventDispacher);
        $lyric->save();
    }
    
    public function getCachedTopNew()
    {
        throw new \Exception('not implemented');
        $cache = $this->cache->get('tekstove.lyric.topNew');
        if ($cache) {
            return $cache;
        }
        
        $newestQuery = new LyricQuery();
        /* @var $newestQuery \Tekstove\TekstoveBundle\Model\LyricQuery */
        $newestQuery->orderById(Criteria::DESC);
        $newestQuery->limit(10);
        $lastLyricsCollection = $newestQuery->find();
        $lastLyrics = $lastLyricsCollection->getArrayCopy();
        $this->cache->set('tekstove.lyric.topNew', $lastLyrics, 60*15);
        return $lastLyrics;
    }
}
