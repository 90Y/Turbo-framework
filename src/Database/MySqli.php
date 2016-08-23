<?php

namespace Turbol\Interfaces\Database;

class MySqli implements Database
{
    public function __construct($config = [])
    {
        echo __CLASS__;
    }

    public function connect()
    {
        return 'ok';
    }
}
