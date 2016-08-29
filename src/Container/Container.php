<?php

namespace Turbo\Container;

use Turbo\Interfaces\Container\Container as iContainer;

class Container implements iContainer
{
    protected $instances = [];

    protected $singletons = [];

    public function __construct()
    {

    }

    //获取实例
    public function make($class)
    {
        if(isset($this->instances[$class]))
        {
            return $this->instances[$class];
        }
        return null;
    }

    //存储实例
    public function instance($class)
    {
        $this->instances[$class] = $class;
    }

    //获取新建实例
    public function build($class)
    {

    }

    //创建单例
    public function singleton($class)
    {

        $this->singletons[$class] = $class;
    }

    //解析class 返回object 及参数
    protected function getIntances($class)
    {

    }
}
