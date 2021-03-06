<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Tekstove\ApiBundle\EventDispatcher\Event;

use Tekstove\ContentChecker\Checker\CheckerInterface;

/**
 * Description of LyricCensoreCacheSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricCensorCacheSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     *
     * @var CheckerInterface
     */
    private $checker;
    
    /**
     * @param CheckerInterface $checker
     */
    public function __construct(CheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    
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

        if ($lyric->isManualCensor()) {
            $lyric->setcacheCensor(true);
        } elseif ($this->checker->isSafe($lyric->getText())) {
            $lyric->setCacheCensor(false);
        } else {
            $lyric->setCacheCensor(true);
        }
        
        $lyric->setcacheCensorUpdated(time());
        return true;
    }
}
