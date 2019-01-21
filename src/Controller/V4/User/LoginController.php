<?php

namespace App\Controller\V4\User;

use App\Controller\V4\TekstoveController;

class LoginController extends TekstoveController
{
    /**
     * Allow only specific groups!
     */
    protected function setGroups(array $groups)
    {
        $this->getContext()->setGroups(
            ['Details', 'Credentials']
        );
    }

    public function getAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->createAccessDeniedException("User not logged");
        }

        $this->getContext()->setGroups(['Details', 'Credentials']);
        return $this->handleEntity($user);
    }
}
