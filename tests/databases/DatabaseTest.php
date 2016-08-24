<?php

use Turbol\Database\MySqli;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public function connectTest()
    {
        $config = [];
        $mysqli = new MySqli($config);
        $this->assertEquals('ok', $mysqli->connect());
    }
}
