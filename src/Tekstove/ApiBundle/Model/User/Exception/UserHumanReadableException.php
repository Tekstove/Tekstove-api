<?php

namespace Tekstove\ApiBundle\Model\User\Exception;

use Tekstove\ApiBundle\Exception\HumanReadableInterface;

/**
 * Description of UserHumanReadableException
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class UserHumanReadableException extends UserException implements HumanReadableInterface
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
