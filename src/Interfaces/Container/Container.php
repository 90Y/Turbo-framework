<?php

namespace Turbo\Interfaces\Container;

interface Container
{
    //获取实例
    public function make($abstract, array $parameters = []);

    //存储实例
    public function instance($abstract, $concrete);

    //实例创建
    public function build($concrete, array $parameters = []);

    //存储单例
    public function singleton($abstract, array $parameters = []);

}
