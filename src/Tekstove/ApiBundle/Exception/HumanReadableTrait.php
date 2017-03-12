<?php

namespace Tekstove\ApiBundle\Exception;

/**
 * Description of HumanReadableTrait
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
trait HumanReadableTrait
{
    private $errors = [];

    public function addError($key, $errorMsg)
    {
        $this->errors[] = [
            'element' => $key,
            'message' => $errorMsg,
        ];
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
