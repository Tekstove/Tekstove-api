<?php

namespace App\Entity\Social;


trait FacebookPageTrait
{
    /**
     * @ORM\Column(type="string")
     */
    private $facebookPageId;

    /**
     * @return string|null
     */
    public function getFacebookPageId()
    {
        return $this->facebookPageId;
    }
}
