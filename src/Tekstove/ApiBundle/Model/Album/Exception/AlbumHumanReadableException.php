<?php

namespace Tekstove\ApiBundle\Model\Album\Exception;

use Tekstove\ApiBundle\Exception\HumanReadableInterface;

/**
 * Description of AlbumHumanReadableException
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class AlbumHumanReadableException extends \Exception implements HumanReadableInterface
{
    use \Tekstove\ApiBundle\Exception\HumanReadableTrait;
}
