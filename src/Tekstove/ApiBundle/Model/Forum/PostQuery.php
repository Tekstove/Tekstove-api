<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\PostQuery as BasePostQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Skeleton subclass for performing query and update operations on the 'forum_post' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PostQuery extends BasePostQuery
{
    use \Tekstove\ApiBundle\Validator\ValidationableTrait;
    
    private $eventDispacher;
    private $validator;
    
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
        
    public function setEventDispacher(EventDispatcherInterface $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }

    public function save(Post $post)
    {
        $post->setValidator($this->validator);
        $post->setEventDispacher($this->eventDispacher);
        $post->save();
    }
}
