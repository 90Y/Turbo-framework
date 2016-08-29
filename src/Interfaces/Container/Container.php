<?php

namespace Turbo\Interfaces\Container;

interface Container
{
    //获取实例
    public function make($class);

    //存储实例
    public function instance($class);

    //获取新建实例
    public function build($class);

    //创建单例
    public function singleton($class);

}
