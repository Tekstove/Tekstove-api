<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\Topic as BaseTopic;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\Forum\Topic\TopicHumanReadableException;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'forum_topic' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Topic extends BaseTopic
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->getValidator())) {
            $errors = $this->getValidationFailures();
            $exception = new TopicHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        return parent::preSave($con);
    }
    
    public function getLatestPost()
    {
        $postQuery = new PostQuery();
        $postQuery->filterByTopic($this);
        $postQuery->orderById(Criteria::DESC);
        $post = $postQuery->findOne();
        return $post;
    }
}
