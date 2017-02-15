<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Symfony\Component\HttpFoundation\RequestStack;
use Tekstove\ApiBundle\EventDispatcher\Event;
use Predis\Client;

/**
 * Description of LyricCounterSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricCounterSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    private $redisClient;
    private $requestStack;

    public function __construct(Client $redisClient, RequestStack $requestStack)
    {
        $this->redisClient = $redisClient;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tekstove.lyric.view' => 'viewEvent',
        ];
    }

    public function viewEvent(Event $event)
    {
        if (!$event instanceof \Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent) {
            return false;
        }
        $lyric = $event->getLyric();

        try {
            $this->redisClient->sadd(
                'lyric.views.' . $lyric->getId(),
                $this->requestStack->getCurrentRequest()->getClientIp()
            );

            $this->redisClient->sadd(
                'lyric.views',
                $lyric->getId()
            );
        } catch (\Exception $e) {
            // @TODO log in monolog
            error_log($e);
        }
    }
}
