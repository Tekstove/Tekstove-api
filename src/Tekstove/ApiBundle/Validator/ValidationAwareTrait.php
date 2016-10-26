<?php

namespace Tekstove\ApiBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ValidationAwareTrait
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
trait ValidationAwareTrait
{

    private $validator;

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    public function getValidator()
    {
        if ($this->validator === null) {
            throw new \RuntimeException("Validator not set");
        }
        return $this->validator;
    }
}
