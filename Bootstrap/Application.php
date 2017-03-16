<?php

//define APPPATH
defined('APPPATH') or define('APPPATH', __DIR__ . '/../app/');
//define APPNAME
defined('APPNAME') or define('APPNAME', basename(APPPATH));

$app = new Turbo\Operation\Application(APPPATH);
$app->bind([\Turbo\Http\Http::class => 'HttpKernel'], \Turbo\Http\Http::class);

return $app;
