<?php

namespace Tekstove\ApiBundle\Exception;

/**
 * Exception implementing this interface could be shown to the user
 *
 * @author po_taka
 */
interface HumanReadableInterface
{
    public function addError($key, $errorMsg);
    public function getErrors();
}
