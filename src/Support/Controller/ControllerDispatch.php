<?php

/**
 * ControllerDispatch
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Support/Controller
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Support\Controller;

use ReflectionClass;
use ReflectionMethod;
use Turbo\Exception\TurboException as Exception;

class ControllerDispatch
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param  $controller
     * @param  $action
     * @param  $params
     * @return mixed
     */
    public function dispatch($controller, $action, $params)
    {
        $reflection = new ReflectionClass($controller);
        $methods    = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if ($method->name == $action) {
                $control = $reflection->newInstanceArgs([$this->app, $controller, $action, $params]);

                return $control->$action();
            }
        }
        throw new Exception('action not matched!');
    }
}
