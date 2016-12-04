<?php

namespace Tekstove\ApiBundle\Model\Chat\Exception;

use Tekstove\ApiBundle\Exception\HumanReadableInterface;

/**
 * Description of MessageHumanReadableException
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessageHumanReadableException extends \Tekstove\ApiBundle\Exception\Exception implements HumanReadableInterface
{
    use \Tekstove\ApiBundle\Exception\HumanReadableTrait;
}
