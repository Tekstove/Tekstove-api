<?php

namespace Tekstove\ApiBundle\Populator;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Tekstove\ApiBundle\Model\User;
use Potaka\Helper\Casing\CaseHelper;

/**
 * PathPopulator
 *
 * @author po_taka
 */
class PathPopulator
{
    /**
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User|false Get logged user
     */
    protected function getUser()
    {
        $token = $this->tokenStorage->getToken();
        return $token->getUser();
    }

    protected function isLogged()
    {
        return $this->getUser() instanceof User;
    }

    public function populateObject($data, $object)
    {
        // only logged users can change stuff
        if (!$this->isLogged()) {
            return false;
        }

        $currentUser = $this->getUser();

        switch (get_class($object)) {
            case User::class:
                $allowedFields = $currentUser->getAllowedUserFields($object);
                break;
            default:
                throw new \RuntimeException("Not implemented for class " . get_class($object));
        }

        $caseHelper = new CaseHelper();
        foreach ($allowedFields as $field) {
            foreach ($data as $path) {
                switch ($path['op']) {
                    case 'replace':
                        if ($path['path'] === '/' . $field) {
                            $bumpyCase = $caseHelper->bumpyCase($field);
                            $setter = 'set' . $bumpyCase;
                            $value = $path['value'];
                            $object->{$setter}($value);
                        }
                        break;
                    default:
                        throw new \Exception('not implemented `op`');
                }
            }
        }
    }
}
