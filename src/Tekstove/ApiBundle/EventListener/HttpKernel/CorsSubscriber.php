<?php

namespace Tekstove\ApiBundle\EventListener\HttpKernel;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsSubscriber implements EventSubscriberInterface
{
    
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onResponse'
        ];
    }

    public function onResponse(FilterResponseEvent $filterResponseEvent)
    {
        $response = $filterResponseEvent->getResponse();
        $response->headers
            ->set('Access-Control-Allow-Origin', '*');
    }
}
