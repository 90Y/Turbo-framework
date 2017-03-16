<?php
/**
 * Middleware
 * @version   SVN:$Id: Middleware.php 22367 2016-12-14 03:27:02Z kakaxi $
 * @package   Turbo/Middleware
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Middleware;

use Turbo\Interfaces\Operation\Application;
use Turbo\Interfaces\Middleware\Middleware as iMiddleware;

class Middleware implements iMiddleware
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
     * @param $passable
     * @param $stack
     * @return mixed
     */
    public function handle($passable, $stack)
    {
        //throw new Exception('throw Exception from Middleware - '.__FILE__);
        return $stack($passable);
    }
}
