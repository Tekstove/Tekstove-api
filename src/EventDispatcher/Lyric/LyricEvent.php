<?php

namespace App\EventDispatcher\Lyric;

use App\Entity\Lyric\Lyric;
use Tekstove\ApiBundle\EventDispatcher\Event;

/**
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
    public function getLyric(): Lyric
    {
        return $this->lyric;
    }
}
