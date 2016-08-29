<?php
namespace Turbo\Operation;

use Turbo\Container\Container;

class Application extends Container
{
    public function __construct()
    {
        parent::__construct();
        echo __CLASS__;
    }
}
