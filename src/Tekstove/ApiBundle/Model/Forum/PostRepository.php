<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\EventDispatcher\EventDispacher;

/**
 * Description of PostRepository
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PostRepository
{
    public function __construct(EventDispacher $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }
}
