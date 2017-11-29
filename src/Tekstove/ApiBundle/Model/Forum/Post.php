<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Propel\Runtime\Connection\ConnectionInterface;
use Tekstove\ApiBundle\Model\Forum\Base\Post as BasePost;
use Tekstove\ApiBundle\Model\Forum\Post\PostHumanReadableException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tekstove\ApiBundle\EventDispatcher\Forum\Post\PostEvent;

/**
 * Skeleton subclass for representing a row from the 'forum_post' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Post extends BasePost
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    
    private $eventDispacher;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->validator)) {
            $errors = $this->getValidationFailures();
            $exception = new PostHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        $this->notifyPreSave($this);
        
        $return = parent::preSave($con);

        $this->notifyPreSaveCompleted($this);

        return $return;
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

    public function setEventDispacher(EventDispatcherInterface $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }

    private function notifyPreSave(Post $post)
    {
        $event = new PostEvent($post);
        $this->getEventDispacher()->dispatch('tekstove.forum.post.save', $event);
    }

    private function notifyPreSaveCompleted(Post $post)
    {
        $event = new PostEvent($post);
        $this->getEventDispacher()->dispatch('tekstove.forum.post.save.completed', $event);
    }
    
    public function getDateTimeTimestamp()
    {
        return $this->getDate('U');
    }
}
