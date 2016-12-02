<?php

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

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
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
