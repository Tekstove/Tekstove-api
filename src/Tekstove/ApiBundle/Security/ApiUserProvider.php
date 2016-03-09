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
        // make a call to your webservice here
        $userQuery = UserQuery::create();
        $userQuery->filterByUsername($username, Criteria::EQUAL);
        $user = $userQuery->findOne();
        // pretend it returns an array on success, false if there is no user

        if ($user) {
            $password = $user->getPassword();
            // @TODO fix roles
            return new SecurityUser($username, $password, '', []);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
    
    public function getUsernameForApiKey($key)
    {
        //@TODO fixme, atm key=username
        $userQuery = UserQuery::create();
        $userQuery->filterByUsername($key, Criteria::EQUAL);
        $user = $userQuery->findOne();
        return $user;
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