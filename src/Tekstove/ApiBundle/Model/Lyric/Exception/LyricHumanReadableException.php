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
