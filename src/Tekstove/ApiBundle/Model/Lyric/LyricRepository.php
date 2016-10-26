<?php

namespace Tekstove\ApiBundle\Model\Lyric;

use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\LyricQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\EventDispatcher\EventDispacher;

/**
 * Description of LyricRepository
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class LyricRepository
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    
    private $eventDispacher;

    public function __construct(EventDispacher $eventDispacher)
    {
        $this->eventDispacher = $eventDispacher;
    }
    
    
}
