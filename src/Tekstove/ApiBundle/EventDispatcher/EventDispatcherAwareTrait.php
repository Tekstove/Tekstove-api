<?php

namespace Tekstove\ApiBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Description of EventDispatcherAwareTrait
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
trait EventDispatcherAwareTrait
{
    private $eventDispacher;
    
    public function setEventDispacher(EventDispatcherInterface $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }

    /**
     *
     * @return EventDispatcherInterface
     * @throws \RuntimeException
     */
    public function getEventDispacher()
    {
        if ($this->eventDispacher === null) {
            throw new \RuntimeException("EventDispacher not set");
        }
        return $this->eventDispacher;
    }
}
