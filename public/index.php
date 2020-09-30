<?php

require_once '../vendor/autoload.php';

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new App\Kernel($_SERVER['APP_ENV'], true);
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

