<?php

namespace Turbol\Interfaces\Database;

class MySqli implements Database
{
    public function __construct()
    {
        echo __CLASS__;
    }
}
