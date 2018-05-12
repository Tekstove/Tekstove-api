<?php

namespace Tekstove\ApiBundle\Security;

/**
 * Description of ApiKeyAuthenticator
 *
 * @author po_taka
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $this->getApiKey($request);

        if (!$apiKey) {
            // do not auth user
            return null;
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }
    
    protected function getApiKey(Request $request)
    {
        $apiKey = $request->get('tekstove-apikey');
        if ($apiKey) {
            return $apiKey;
        }

        $apiKey = $request->headers->get('tekstove-apikey');
        
        return $apiKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();
        $user = $userProvider->findUserByApiKey($apiKey);

        if (!$user) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException("API Key '{$apiKey}' does not exist.");
        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            ['registered']
        );
    }

    /**
     * @return boolean
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        if (!$token instanceof PreAuthenticatedToken) {
            return false;
        }
        return $token->getProviderKey() === $providerKey;
    }
}
