<?php

namespace Tekstove\ApiBundle\Model\User;

use Tekstove\ApiBundle\Model\User\Base\Pm as BasePm;
use Propel\Runtime\Connection\ConnectionInterface;
use Tekstove\ApiBundle\Model\User\Pm\Exception\PmHumanReadableException;

/**
 * Skeleton subclass for representing a row from the 'pm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Pm extends BasePm
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->getValidator())) {
            $errors = $this->getValidationFailures();
            $exception = new PmHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        return parent::preSave($con);
    }
    
    public function getDateTimeTimestamp()
    {
        return $this->getDatetime('U');
    }
}
