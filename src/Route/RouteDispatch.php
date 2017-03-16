<?php
/**
 * RouteDispatch
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/RouteDispatch
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Route;

use Closure;
use Turbo\Exception\TurboException as Exception;
use Turbo\Support\Controller\ControllerDispatch;

class RouteDispatch
{
    /**
     * @var string
     */
    public static $limitChar = '/';

    /**
     * @var array
     */
    public static $store = [];


    /**
	 * 分组路由
     * @var array
     */
    public static $groups = [];

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments) {}

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
		//pad 3 elemetns
		$arguments = array_pad($arguments, 3, Null);
		list($method, $parameter, $group) = $arguments;

		$name                     = strtoupper($name);
		//self::$store[$method][$name] = $parameter;
		if (is_string($parameter) && (strpos($parameter, '/') === false)) {
			self::$store[$name]['turbo_$'][$method] = $parameter;
		} else {
			self::$store[$name][$method] = $parameter;
		}
		if ($group !== Null)
		{
			self::$groups[$name][$group][$method] = '';
		}
	}

    /**
     * @param $next
     */
    public static function chunkUri($next)
    {
        $uris = array_filter(explode(self::$limitChar, $next));
        switch ($count = count($uris)) {
            case 1:
                $control = array_shift($uris);
                $action  = 'index';
                break;
            case $count >= 2:
                $control = array_shift($uris);
                $action  = array_shift($uris);
                break;

            default:
                throw new Exception('Controller not match!');
        }

        //combine params data
        $params = ['keys' => [], 'vals' => []];
        while (list($key, $val) = each($uris)) {
            if ($key % 2 == 0) {
                $params['keys'][] = $val;
            } else {
                $params['vals'][] = $val;
            }
        }
        if (count($params['keys']) != count($params['vals'])) {
            $params['vals'] = array_pad($params['vals'], count($params['keys']), '');
        }

        return [$control, $action, array_combine($params['keys'], $params['vals'])];
    }

    /**
     * @param $method
     * @param $uri
     * @return mixed
     */
    public static function parseUri($method, $uri)
    {
        $methods = self::$store[$method];
        if (isset($methods[$uri])) {
            return $methods[$uri];
        }

        if (isset($methods['turbo_$'])) {
            //controller是否存在在 turbo_$ 中
            $uris = explode(self::$limitChar, $uri);
            $key  = '/' . array_shift($uris);

            if (isset($methods['turbo_$'][$key])) {
                return $methods['turbo_$'][$key] . '/' . implode(self::$limitChar, $uris);
            }
        }

        return null;
    }

    /**
     * @param $request
     * @param $app
     */
    public static function run($request, &$app)
    {
        $uri    = $request->getPathInfo();
        $method = $request->getMethod();

        $next = self::parseUri($method, $uri);

        if ($next === null) {
            throw new Exception('route ' . $uri . '-' . $method . ' is not configure.');
        }

        //callable
        if ($next instanceof Closure) {
            return self::runCallback($next, $app);
        }

        //controller
        if (is_string($next)) {
            return self::runControll($next, $app);
        }

        throw new Exception('route ' . $uri . '-' . $method . ' is not configure.');
    }

    /**
     * @param  $next
     * @param  $app
     * @return mixed
     */
    public static function runCallback($next, $app)
    {
        return $next($app);
    }

    /**
     * @param $next
     * @param $app
     */
    public static function runControll($next, $app)
    {
        list($control, $action, $params) = self::chunkUri($next);
        $control                         = $control . 'Controll';
        if (class_exists($control)) {
            return (new ControllerDispatch($app))->dispatch($control, $action, $params);
        }
        throw new Exception('Controller not found!');
    }
}
