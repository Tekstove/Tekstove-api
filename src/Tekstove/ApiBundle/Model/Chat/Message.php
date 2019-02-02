<?php

namespace Tekstove\ApiBundle\Model\Chat;

use Propel\Runtime\Connection\ConnectionInterface;
use Tekstove\ApiBundle\Model\Chat\Base\Message as BaseMessage;
use Tekstove\ApiBundle\EventDispatcher\Chat\MessageEvent;

use Tekstove\ApiBundle\Model\Chat\Exception\MessageHumanReadableException;

/**
 * Skeleton subclass for representing a row from the 'chat' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Message extends BaseMessage
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    use \Tekstove\ApiBundle\EventDispatcher\EventDispatcherAwareTrait;

    public function preSave(\Propel\Runtime\Connection\ConnectionInterface $con = null)
    {
        if (!$this->validate($this->getValidator())) {
            $errors = $this->getValidationFailures();
            $exception = new MessageHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }

        $eventValidators = new MessageEvent($this);
        $this->getEventDispacher()->dispatch('tekstove.chat.message.validate.after', $eventValidators);

        $event = new MessageEvent($this);
        $this->getEventDispacher()->dispatch('tekstove.chat.message.save', $event);
        return parent::preSave($con);
    }

    public function postSave(ConnectionInterface $con = null)
    {
        parent::postSave($con);

        $event = new MessageEvent($this);
        $this->getEventDispacher()->dispatch('tekstove.chat.message.save.post', $event);
    }

    public function getDateTimeTimestamp()
    {
        return $this->getDate('U');
    }
}
