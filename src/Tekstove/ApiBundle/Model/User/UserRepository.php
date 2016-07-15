<?php

namespace Tekstove\ApiBundle\Model\User;

use Tekstove\ApiBundle\EventDispatcher\EventDispacher;
use Tekstove\ApiBundle\Model\User;

/**
 * Description of UserRepository
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class UserRepository
{
    use \Tekstove\ApiBundle\Validator\ValidationableTrait;
    
    private $eventDispacher;

    public function __construct(EventDispacher $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }
    
    public function save(User $user)
    {
        $user->setValidator($this->validator);
        $user->save();
    }
}
