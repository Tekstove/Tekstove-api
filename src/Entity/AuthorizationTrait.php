<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait AuthorizationTrait
{
    /**
     * @ORM\Column(type="integer")
     * Hold data if we have legal permission to show lyrics
     */
    private $authorization = AuthorizationInterface::AUTHORIZATION_NA;

    /**
     * @return int see constants in AuthorizationInterface
     */
    public function getAuthorization(): int
    {
        return $this->authorization;
    }

    /**
     * @param int $authorization
     */
    public function setAuthorization(int $authorization): void
    {
        $this->authorization = $authorization;
    }
}
