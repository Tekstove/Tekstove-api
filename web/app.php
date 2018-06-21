<?php

// @FIXME fix this file!

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

// see https://github.com/Tekstove/Tekstove/issues/80
Request::setTrustedHeaderName(Request::HEADER_FORWARDED, null);
Request::setTrustedProxies(
    [
        "79.98.109.18",
        "127.0.0.1",
        "103.21.244.0/22",
        "103.22.200.0/22",
        "103.31.4.0/22",
        "104.16.0.0/12",
        "108.162.192.0/18",
        "131.0.72.0/22",
        "141.101.64.0/18",
        "162.158.0.0/15",
        "172.64.0.0/13",
        "173.245.48.0/20",
        "188.114.96.0/20",
        "190.93.240.0/20",
        "197.234.240.0/22",
        "198.41.128.0/17",
        "199.27.128.0/21",
    ]
);

// I know this is ugly...but...sry...
function errorToException($errNumber, $errMsg, $errFile, $errLine)
{
    $errorMsg = "Error#{$errNumber}. $errMsg in $errFile : $errLine";
    \error_log($errorMsg);

    if (error_reporting() === 0) {
        return true;
    }
     throw new \Exception($errMsg);
}

set_error_handler('errorToException');

$kernel = new AppKernel('prod', false);
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
