<?php

namespace Tekstove\ApiBundle\Exception;

/**
 * Description of HumanReadableException
 *
 * @author po_taka
 */
class HumanReadableException extends Exception implements HumanReadableInterface
{
    use HumanReadableTrait;
}
