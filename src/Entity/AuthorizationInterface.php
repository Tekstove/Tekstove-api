<?php

namespace App\Entity;


interface AuthorizationInterface
{
    const AUTHORIZATION_NA = 0;
    const AUTHORIZATION_FORBIDDEN = 1;
    const AUTHORIZATION_ALLOWED = 2;

    public function getAuthorization(): int;
    public function setAuthorization(int $authorization);
}
