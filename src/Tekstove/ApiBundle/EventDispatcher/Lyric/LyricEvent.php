<?php

namespace Tekstove\ApiBundle\EventDispatcher\Lyric;

use Tekstove\ApiBundle\EventDispatcher\Event;

use Tekstove\ApiBundle\Model\Lyric;

/**
 * Description of LyricEvent
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricEvent extends Event
{
    private $lyric;
    
    public function __construct(Lyric $lyric)
    {
        $this->lyric = $lyric;
    }
    
    /**
     * @return Lyric
     */
    public function getLyric()
    {
        return $this->lyric;
    }
}
