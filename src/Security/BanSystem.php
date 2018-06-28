<?php

namespace App\Security;

use Predis\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author po_taka
 */
class BanSystem
{
    /**
     * Used to prefix all redis keys
     */
    const REDIS_PREFIX = 'ban.';

    private $redis;

    private $requestStack;

    /**
     * @param Client $redis
     * @param RequestStack $requestStack
     */
    public function __construct(Client $redis, RequestStack $requestStack)
    {
        $this->redis = $redis;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $ip
     * @param int $seconds
     */
    public function banIp($ip, $seconds, $message)
    {
        $ipBanExists = $this->isIpBanned($ip);

        $ipRedisKey = $this->getRedisIpKey($ip);

        // if current ban is for longer period do not overwrite
        if ($ipBanExists) {
            $currentTtl = $this->redis->ttl(
                $ipRedisKey
            );

            if ($currentTtl > $seconds) {
                return null;
            }
        }

        $this->redis->setEx(
            $ipRedisKey,
            $seconds,
            $message
        );
    }

    public function isIpBanned($ip) : bool
    {
        return $this->redis->exists(
            $this->getRedisIpKey($ip)
        );
    }

    /**
     * @param string $ip
     *
     * @return string
     */
    public function getRedisIpKey($ip) : string
    {
        return self::REDIS_PREFIX . $ip;
    }
}
