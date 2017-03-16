<?php

/**
 * 框架底层http处理类
 *
 * @version   svn:$id: http.php 4533 2016-11-04 09:39:35z kakaxi $
 * @package   turbo/http
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Http;

use Turbo\Http\Request;
use Turbo\Http\Response;
use Turbo\Pipeline\Pipeline;
use Turbo\Interfaces\Route\Router;
use Turbo\Exception\TurboException;
use Turbo\Interfaces\Operation\Application;

class Http
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $router;

    /**
     * @param Application $app
     * @param Router $router
     */
    public function __construct(Application $app, Router $router)
    {
        $this->app    = $app;
        $this->router = $router;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        try {
            $this->request = $request;

			$pipe =  (new Pipeline($this->app));
			$middls = $this->app->shouldSkipMiddleware() ? [] : $this->app->getMiddleware('inputs');

            $ret = $pipe->send($this->request)->through($middls)->then($this->dispatchToRouter());

			$middlsOut = $this->app->shouldSkipMiddleware() ? [] : $this->app->getMiddleware('outputs');
			if($middlsOut) {
                $ret = $pipe->send($ret)->via('handleOut')->through($middlsOut)->then(function($response){return $response;});
			}
        } catch (TurboException $e) {
            $ret = new Response($e->exitException());
        }
        return $ret;
    }

    /**
     * 结束
     */
    public function terminate()
    {
        //var_dump('terminate');
    }

    /**
     * 分发路由
     * return Closure
     */
    protected function dispatchToRouter()
    {
        return function ($request) {
            //存储实例
            $this->app->instance(\Turbo\Http\Request::class, $request);

            return $this->router->dispatch($request);
        };
    }
}
