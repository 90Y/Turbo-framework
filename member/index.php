<?php
ini_set('date.timezone', 'Asia/Shanghai');

define('APPPATH', __DIR__ . '/');
require '../Bootstrap/autoload.php';

$app = include '../Bootstrap/Application.php';

$http     = $app->make(Turbo\Http\Http::class);
$response = $http->handle($request = (new Turbo\Http\Request())->start());
$response->send();
$http->terminate();
