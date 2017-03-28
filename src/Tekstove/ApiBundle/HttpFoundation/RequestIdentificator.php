<?php

namespace Tekstove\ApiBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 *
 * Description of RequestIdentificator
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class RequestIdentificator
{
    public function identify(Request $request)
    {
        $ips = $request->getClientIps();
        $ipsAsString = implode('|', $ips);

        $userAgent = $request->headers->get('user-agent');
        if (is_array($userAgent)) {
            $userAgent = implode('|', $userAgent);
        }

        $hash = sha1($ipsAsString . $userAgent);

        return $hash;
    }
}
