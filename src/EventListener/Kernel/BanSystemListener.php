<?php

namespace App\EventListener\Kernel;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Tekstove\ApiBundle\Security\BanSystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author po_taka
 */
class BanSystemListener
{
    /**
     * @var BanSystem
     */
    private $banSystem;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     * @param BanSystem $banSystem
     */
    public function __construct(RequestStack $requestStack, BanSystem $banSystem)
    {
        $this->banSystem = $banSystem;
        $this->requestStack = $requestStack;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        // do not filter get requests
        if ($currentRequest->isMethod('GET')) {
            return;
        }

        $ip = $this->requestStack->getCurrentRequest()->getClientIp();

        $isIpBanned = $this->banSystem->isIpBanned($ip);

        if ($isIpBanned) {
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
            return false;
        }
    }
}
