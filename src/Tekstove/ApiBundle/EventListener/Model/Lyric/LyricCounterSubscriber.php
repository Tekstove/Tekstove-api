<?php

namespace Tekstove\ApiBundle\EventListener\Model\Lyric;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Tekstove\ApiBundle\EventDispatcher\Event;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Potaka\IpAnonymizer\IpAnonymizer;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricCounterSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private $logger;
    private $redisClient;
    private $requestStack;
    private $tokenStorage;
    private $ipAnonymizer;

    public function __construct(Client $redisClient, RequestStack $requestStack, LoggerInterface $logger, TokenStorageInterface $tokenStorage, IpAnonymizer $ipAnonymizer)
    {
        $this->redisClient = $redisClient;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->tokenStorage = $tokenStorage;
        $this->ipAnonymizer = $ipAnonymizer;
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

        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            $viewKey = 'u' . $user->getId();
        } else {
            $viewKey = $this->ipAnonymizer->anonymize(
                $this->requestStack->getCurrentRequest()->getClientIp()
            );
        }

        try {
            $this->redisClient->sadd(
                'lyric.views.' . $lyric->getId(),
                $viewKey
            );

            $this->redisClient->sadd(
                'lyric.views',
                $lyric->getId()
            );
        } catch (\Exception $e) {
            $this->logger->emergency('Can\'t write view', [$e]);
        }
    }
}
