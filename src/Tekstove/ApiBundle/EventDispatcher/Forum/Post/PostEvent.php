<?php

namespace Tekstove\ApiBundle\EventDispatcher\Forum\Post;

use Tekstove\ApiBundle\EventDispatcher\Event;
use Tekstove\ApiBundle\Model\Forum\Post;

/**
 * Description of PostEvent
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PostEvent extends Event
{
    private $post;
    
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    
    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
