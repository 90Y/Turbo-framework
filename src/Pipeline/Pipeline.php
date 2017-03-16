<?php
/**
 * Pipeline
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Pipeline
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

/**
 * //start
 * $passable  = 10;
 * //end
 * $dest = function ($passable)
 * {
 * return $passable;
 * };
 *
 * //each pipe in array
 * $pipe = function ($passable, $stack)
 * {
 * $passable *= 10;
 * return $stack($passable);
 * };
 *
 * //pipes
 * $pipes = array(
 * $pipe,
 * $pipe,
 * //function($passable,$stack,$a=''){$passable->num += 10;return $stack($passable);},
 * //new HandleClass(), must exists class named HandClass and exists handle method
 * //'HandleClass', look up
 * );
 * $ret = (new Pipeline())->via('handled')->send($passable)->through($pipes)->then($dest);
 * var_dump($ret);
 */

namespace Turbo\Pipeline;

use Closure;
use Turbo\Interfaces\Operation\Application;

class Pipeline
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var string
     */
    protected $method = 'handle';

    /**
     * @var mixed
     */
    private $passable;

    /**
     * 构造函数
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 设置需要操作的起始
     * @param  mixed   $passable
     * @return $this
     */
    public function send($passable)
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * 调用管道并调用回调
     * @param  \Closure   $dest
     * @return \Closure
     */
    public function then(Closure $dest)
    {
        $firstSlice = $this->getInitialSlice($dest);
        $pipes      = array_reverse($this->pipes);

        return call_user_func(
            array_reduce($pipes, $this->getSlice(), $firstSlice), $this->passable);
    }

    /**
     * 设置调用的pipes
     * @param  array   $pipes
     * @return $this
     */
    public function through(array $pipes)
    {
        $this->pipes = $pipes;

        return $this;
    }

    /**
     * 设置调用对象的方法
     * @param string  $method
     * @param $this
     */
    public function via($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * 获取起始stack 调用函数
     * @param  \Closure   $dest
     * @return \Closure
     */
    private function getInitialSlice(Closure $dest)
    {
        return function ($passable) use ($dest) {
            return call_user_func($dest, $passable);
        };
    }

    /**
     * 获取管道提供的回调
     * @return \Closure
     */
    private function getSlice()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                if ($pipe instanceof Closure) {
                    return call_user_func($pipe, $passable, $stack);
                } else if (!is_object($pipe)) {
                    //new obj and make parameters
                    //$pipe       = new $pipe;
                    $pipe       = $this->app->build($pipe);
                    $parameters = [$passable, $stack];
                } else {
                    $parameters = [$passable, $stack];
                }

                return call_user_func_array([$pipe, $this->method], $parameters);
            };
        };
    }
}
