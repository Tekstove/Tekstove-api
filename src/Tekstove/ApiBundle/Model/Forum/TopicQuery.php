<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\TopicQuery as BaseTopicQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'forum_topic' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class TopicQuery extends BaseTopicQuery
{
    use \Tekstove\ApiBundle\Model\RepositoryTrait;
    
    public function save(Topic $topic)
    {
        $topic->setValidator($this->getValidator());
        $topic->save();
    }
}
