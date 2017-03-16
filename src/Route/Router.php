<?php
/**
 * Router
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Route
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Route;

use Turbo\Http\Response;
use Turbo\Route\RouteDispatch;
use Turbo\Interfaces\Operation\Application;
use Turbo\Interfaces\Route\Router as iRouter;
use Turbo\Exception\TurboException as Exception;

class Router implements iRouter
{
    /**
     * @var mixed
     */
    protected $app = null;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $request
     */
    public function dispatch($request)
    {
        //include Route.php
        include $this->app->appHttpPath() . 'Route.php';
        try {
            //add middlewaware
            //judge which way to deal
            $response = RouteDispatch::run($request, $this->app);
        } catch (Exception $exception) {
            $response = $exception->exitException();
        }

        return new Response($response);
    }
}
