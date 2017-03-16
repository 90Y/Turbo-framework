<?php
/**
 * Application
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Operation/Bootstrap
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Operation;

use Turbo\Container\Container;
use Turbo\Interfaces\Operation\Application as iApplication;

class Application extends Container implements iApplication
{
    /**
     * @var mixed
     */
    protected $basePath;

    /**
     * 初始化核心套件
     * @var array
     */
    protected $coreSuit = [
        [\Turbo\Interfaces\Operation\Application::class => 'app'],
        \Turbo\Route\Router::class => [\Turbo\Interfaces\Route\Router::class => 'router'],
        \Turbo\Log\Log::class      => [\Turbo\Interfaces\Log\Log::class => 'log'],
    ];

    /**
     * @var array
     */
    protected $middleware = [
        //'test' => '\Turbo\Middleware\Middleware',
    ];

    /**
     * rootPath
     * @var mixed
     */
    protected $rootPath;

    /**
     * @param $basePath
     */
    public function __construct($basePath = '')
    {
        $this->basePath = $basePath;
        $this->rootPath = dirname(__DIR__);

        defined('CFGPATH') or define('CFGPATH', $this->basePath . 'Config/');
        $this->registerCoreSuit();
        $this->registerSplAutoload();
        $this->registerClassAlias();
        $this->registerSessionHandle();
    }

    /**
     * 返回application cache path
     */
    public function appCachePath()
    {
        return $this->basePath . 'Cache/';
    }

    /**
     * 返回application config path
     */
    public function appConfigPath()
    {
        return $this->basePath . 'Config/';
    }

    /**
     * 返回application controller path
     */
    public function appControllerPath()
    {
        return $this->appHttpPath() . 'Controller/';
    }

    /**
     * 返回application controller path
     */
    public function appHttpPath()
    {
        return $this->basePath . 'Http/';
    }

    /**
     * 返回application log path
     */
    public function appLogPath()
    {
        return $this->basePath . 'log/';
    }

    /**
     * 返回application middle path
     */
    public function appMiddlewarePath()
    {
        return $this->appPath() . 'Middleware/';
    }

    /**
     * 返回application model path
     */
    public function appModelPath()
    {
        return $this->appHttpPath() . 'Model/';
    }

    /**
     * 返回application path
     */
    public function appPath()
    {
        return $this->basePath;
    }

    /**
     * 返回application service path
     */
    public function appServicePath()
    {
        return $this->appHttpPath() . 'Service/';
    }

    /**
     * get MiddleWares
     */
    public function getMiddleware($way)
    {
        $appMiddles = config($way, 'middlewares');

        return array_merge($this->middleware, (array) $appMiddles);
    }

    /**
     * 是否跳过中间件
     * @return boolean
     */
    public function shouldSkipMiddleware()
    {
        return false;
    }

    /**
     * 注册类别名
     */
    protected function registerClassAlias()
    {
        // all in class => class alias
        class_alias(\Turbo\Route\RouteDispatch::class, 'Route');
    }

    /**
     * 初始化首次注册核心函数
     */
    protected function registerCoreSuit()
    {
        //include helper
        include $this->rootPath . '/Support/helper.php';

        foreach ($this->coreSuit as $abstract => $suit) {
            $this->bind($suit, is_string($abstract) ? $abstract : function () {return $this;});
        }
    }

    protected function registerSessionHandle()
    {
        $session = new \Turbo\Http\Session\Session($this);
        $session->handle();
    }

    protected function registerSplAutoload()
    {
        $paths = [
            'Controll'   => $this->appControllerPath(), //app controller
            'Model'      => $this->appModelPath(), //model
            'Service'    => $this->appServicePath(), //services
            'Middleware' => $this->appMiddlewarePath(), //services
        ];

        foreach ($paths as $suffix => $path) {
            spl_autoload_register(function ($className) use ($suffix, $path) {
                $file = $path . $className . '.php';
                if (false !== strpos($className, $suffix) && file_exists($file)) {
                    include $file;
                    return true;
                }
                return false;
            });
        }
    }
}
