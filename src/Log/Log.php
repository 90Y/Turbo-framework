<?php

/**
 * Log
 * @version   SVN:$Id: Log.php 22073 2016-12-13 11:08:11Z kakaxi $
 * @package   Turbo/Log
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Log;

use Turbo\Interfaces\Log\Log as iLog;
use Turbo\Interfaces\Operation\Application;

class Log implements iLog
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var int
     */
    protected $mode = 0777;

    /**
     * @var mixed
     */
    protected $path;

    /**
     * @var string
     */
    protected $suffix = '.log';

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app  = $app;
        $this->path = $this->app->appLogPath();
    }

    public function getTimeDir()
    {
        return date('Y/m/d/H/', time());
    }

    /**
     * @param $msg
     * @param $file
     * @param $type
     */
    public function logIn($msg, $file, $type = '')
    {
        $logPath = $this->path . $this->getTimeDir() . ($type ? '/' . $type . '/' : '') . $file . $this->suffix;

        $logDir = dirname($logPath);

        if (!file_exists($logDir)) {
            mkdir($logDir, $this->mode, true);
        }

        $msg = is_scalar($msg) ? $msg : json_encode($msg);

        file_put_contents($logPath, $msg . PHP_EOL, FILE_APPEND);

    }
}
