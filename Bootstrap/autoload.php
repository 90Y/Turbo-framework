<?php

require __DIR__ . '/../../turbo/vendor/autoload.php';

//namespace Turbo\Autoload;

/*
//register Tuobo Core
spl_autoload_register(function($className){
$classPath = str_replace('Turbo\\', '', $className);
$classPath = TURBO_ROOT . str_replace('\\', '/', $classPath . '.php');
if (file_exists($classPath))
{
include $classPath;
return true;
}
return false;
});

//register each Application
spl_autoload_register(function($className){
echo 'register Application';
var_dump($className);
});
 */
