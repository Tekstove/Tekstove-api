<?php

namespace Tekstove\ApiBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of Validationable
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
trait ValidationableTrait
{

    private $validator;

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
