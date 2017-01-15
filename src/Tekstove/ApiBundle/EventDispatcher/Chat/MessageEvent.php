<?php

namespace Tekstove\ApiBundle\EventDispatcher\Chat;

use Tekstove\ApiBundle\EventDispatcher\Event;
use Tekstove\ApiBundle\Model\Chat\Message;

/**
 * MessageEvent
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageEvent extends Event
{
    private $message;

    public function __construct($object = null, $arguments = [])
    {
        if (!$object instanceof Message) {
            throw new \RuntimeException("Expected instance of Message");
        }

        parent::__construct($object, $arguments);
        $this->message = $object;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
