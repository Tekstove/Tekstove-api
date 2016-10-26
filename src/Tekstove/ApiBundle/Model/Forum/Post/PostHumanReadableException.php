<?php

namespace Tekstove\ApiBundle\Model\Forum\Post;

use Tekstove\ApiBundle\Exception\HumanReadableException;

/**
 * Description of PostHumanReadableException
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PostHumanReadableException extends HumanReadableException
{
    use \Tekstove\ApiBundle\Exception\HumanReadableTrait;
}
