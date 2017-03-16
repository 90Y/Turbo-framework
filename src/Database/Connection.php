<?php

/**
 * Database
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Database
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Database;

use Turbo\Interfaces\Database\Connect as iConnect;

class Connection implements iConnect
{
    /**
     * @var mixed
     */
    public static $db;

    public function __construct()
    {
        //config defines which db engine to use
    }

    public static function connect()
    {

        $dbtype    = config('default', 'database');
        $dbconnect = config('connections', 'database')[$dbtype];
        $prefix    = '\\' . __NAMESPACE__;
        //var_dump($prefix);
        $db = $prefix . '\\' . $dbtype;

        self::$db = new $db($dbconnect);
    }

    public static function getInstance()
    {
        if (null === self::$db) {
            self::connect();
        }

        return self::$db;
    }
}
