<?php

namespace Tekstove\ApiBundle\Security;

use Predis\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author po_taka
 */
class BanSystem
{
    private $redis;

    private $requestStack;

    public function __construct(Client $redis, RequestStack $requestStack)
    {
        /**
         * @var Client
         */
        $this->redis = $redis;
        $this->requestStack = $requestStack;
    }

    /**
     *
     * @param string $ip
     * @param int $seconds
     */
    public function banIp($ip, $seconds, $message)
    {
        $ipBanExists = $this->redis->exists($ip);

        // if current ban is for longer period do not overwrite
        if ($ipBanExists) {
            $currentTtl = $this->redis->ttl($ip);
            if ($currentTtl > $seconds) {
                return null;
            }
        }

        $this->redis->setEx(
            $ip,
            $seconds,
            $message
        );
    }

    public function isIpBanned($ip)
    {
        return $this->redis->exists($ip);
    }
}
