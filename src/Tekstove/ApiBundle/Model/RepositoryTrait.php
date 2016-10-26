<?php

namespace Tekstove\ApiBundle\Model;

/**
 * Description of RepositoryTrait
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
trait RepositoryTrait
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    use \Tekstove\ApiBundle\EventDispatcher\EventDispatcherAwareTrait;
}
