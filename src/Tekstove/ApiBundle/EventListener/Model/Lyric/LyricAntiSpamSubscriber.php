<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricAntiSpamSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'tekstove.lyric.save' => 'saveEvent',
        );
    }

    public function saveEvent(Event $event)
    {
        if (!$event instanceof LyricEvent) {
            return false;
        }
        $lyric = $event->getLyric();

        $match = '/\<a href\=/i';

        if (preg_match($match, $lyric->getText())) {
            $exception = new \Tekstove\ApiBundle\Model\Lyric\Exception\LyricHumanReadableException("Spam filter failed");
            $exception->addError('text', 'Полето не може да съдържа "<a href="');
            throw $exception;
        }

    }
}
