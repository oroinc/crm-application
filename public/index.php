<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

require_once __DIR__.'/../src/AppKernel.php';
//require_once __DIR__.'/../src/AppCache.php';

$kernel = new AppKernel('prod', false);

//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
