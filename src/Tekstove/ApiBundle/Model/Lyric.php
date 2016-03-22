<?php

namespace Tekstove\ApiBundle\Model;

use Propel\Runtime\Connection\ConnectionInterface;
use Tekstove\ApiBundle\Model\Base\Lyric as BaseLyric;

use Tekstove\ApiBundle\EventDispatcher\EventDispacher;
use Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent;

use Tekstove\ApiBundle\Model\Lyric\Exception\LyricHumanReadableException;

/**
 * Skeleton subclass for representing a row from the 'lyric' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Lyric extends BaseLyric
{
    use AclTrait;
    
    private $eventDispacher;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate()) {
            $errors = $this->getValidationFailures();
            $exception = new LyricHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        $this->notifyPreSave($this);
        
        return parent::preSave($con);
    }
    
    /**
     *
     * @return EventDispacher
     */
    private function getEventDispacher()
    {
        if ($this->eventDispacher === null) {
            throw new \Exception('eventDispacher not set');
        }
        return $this->eventDispacher;
    }
    public function setEventDispacher(EventDispacher $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }
    private function notifyPreSave(Lyric $lyric)
    {
        $event = new LyricEvent($lyric);
        $this->getEventDispacher()->dispatch('tekstove.lyric.save', $event);
    }
}
