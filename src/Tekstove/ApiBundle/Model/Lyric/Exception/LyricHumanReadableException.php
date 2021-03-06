<?php

namespace Tekstove\ApiBundle\Model\Lyric\Exception;

use Tekstove\ApiBundle\Exception\HumanReadableInterface;

/**
 * Description of LyricHumanReadableException
 *
 * @author po_taka
 */
class LyricHumanReadableException extends LyricException implements HumanReadableInterface
{

    use \Tekstove\ApiBundle\Exception\HumanReadableTrait;
}
