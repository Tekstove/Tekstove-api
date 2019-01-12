<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait AuthorizationTrait
{
    /**
     * @ORM\Column(type="integer")
     */
    private $authorization = AuthorizationInterface::AUTHORIZATION_NA;

    /**
     * @return mixed
     */
    public function getAuthorization(): int
    {
        return $this->authorization;
    }

    /**
     * @param mixed $authorization
     */
    public function setAuthorization(int $authorization): void
    {
        $this->authorization = $authorization;
    }
}
