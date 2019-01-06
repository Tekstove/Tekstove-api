<?php

namespace Tekstove\ApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Tekstove\ApiBundle\Model\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;

use Tekstove\ApiBundle\Security\User as SecurityUser;

/**
 * Description of ApiUserProvider
 *
 * @author po_taka
 */
class ApiUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $userQuery = UserQuery::create();
        $userQuery->filterByUsername($username, Criteria::EQUAL);
        $user = $userQuery->findOne();
        // pretend it returns an array on success, false if there is no user

        if ($user) {
            $password = $user->getPassword();
            return new SecurityUser($username, $password, '', ['ROLE_USER']);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
    
    public function findUserByApiKey($key)
    {
        $userQuery = UserQuery::create();
        $userQuery->filterByapiKey($key, Criteria::EQUAL);
        $user = $userQuery->findOne();

        $securityUser = new SecurityUser(
            $user->getUsername(),
            $user->getPassword(),
            '',
            ['ROLE_USER']
        );

        $securityUser->setapiKey($user->getapiKey());
        $securityUser->setId($user->getId());
        $securityUser->settermsAccepted($user->gettermsAccepted());
        $securityUser->setMail($user->getMail());
        $securityUser->setNew(false);
        $securityUser->resetModified();

        return $securityUser;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SecurityUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === SecurityUser::class;
    }
}
