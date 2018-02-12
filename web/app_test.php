<?php

use Symfony\Component\HttpFoundation\Request;

umask(0002); // This will let the permissions be 0775

// This check prevents access to debug front controllers 
if (
        !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1', "147.135.200.130")) 
        || php_sapi_name() === 'cli-server')
) {
    exit('You are not allowed to access this file.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('test', true);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);