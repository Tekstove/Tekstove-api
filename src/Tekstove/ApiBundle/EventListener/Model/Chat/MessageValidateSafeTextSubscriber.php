<?php

namespace Tekstove\ApiBundle\EventListener\Model\Chat;

use Tekstove\ContentChecker\Checker\CheckerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Tekstove\ApiBundle\EventDispatcher\Chat\MessageEvent;
use Tekstove\ApiBundle\EventDispatcher\Event;
use Tekstove\ApiBundle\Model\Chat\Exception\MessageHumanReadableException;

/**
 * Description of MessageValidateSafeTextSubscriber
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageValidateSafeTextSubscriber implements EventSubscriberInterface
{
    private $checker;

    public function __construct(CheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'tekstove.chat.message.validate.after' => 'validateEvent',
        ];
    }

    public function validateEvent(Event $event)
    {
        if (!$event instanceof MessageEvent) {
            return false;
        }

        

        $message = $event->getMessage();
        if (!$this->checker->isSafe($message->getMessage())) {
            $exception = new MessageHumanReadableException('Validation failed.');
            $exception->addError('message', 'Съжалява, но съобщението ти изглежда като спам');
            throw $exception;
        }
    }
}
