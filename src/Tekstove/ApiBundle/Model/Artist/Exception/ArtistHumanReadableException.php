<?php

namespace Tekstove\ApiBundle\Model\Artist\Exception;

use Tekstove\ApiBundle\Exception\HumanReadableInterface;

/**
 * Description of ArtistHumanReadableException
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class ArtistHumanReadableException extends \Exception implements HumanReadableInterface
{
    use \Tekstove\ApiBundle\Exception\HumanReadableTrait;
}
