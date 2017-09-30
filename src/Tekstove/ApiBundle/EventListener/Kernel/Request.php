<?php

namespace Tekstove\ApiBundle\EventListener\Kernel;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Predis\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Description of Request
 *
 * @author potaka
 */
class Request
{
    /**
     *
     * @var Client
     */
    private $redisClient;
    private $requestStack;


    public function __construct(RequestStack $requestStack, Client $redisClient) {
        $this->redisClient = $redisClient;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        // do not filter get requests
        if ($currentRequest->isMethod('GET')) {
                        return;
        }

        $ip = $this->requestStack->getCurrentRequest()->getClientIp();

        $isIpBanned = $this->redisClient->exists($ip);

        if ($isIpBanned) {
            // @FIXME
            die('asdasd');
        }
    }
}
