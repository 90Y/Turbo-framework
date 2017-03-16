<?php

/**
 * session 文件储存
 *
 * @version   svn:$id: http.php 4533 2016-11-04 09:39:35z kakaxi $
 * @package   turbo/Http/Session
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Http\Session;

use Turbo\Interfaces\Http\Session\Handler as iHandler;

class FileHandler implements iHandler
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    private $savePath;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        ini_set('session.save_path', $this->app->appCachePath());
        ini_set('session.name', 'app_'); //cookie name prefix
    }

    public function close()
    {
        return true;
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /**
     * @param $maxlifetime
     */
    public function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * @param $savePath
     * @param $sessionName
     */
    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    /**
     * @param $id
     */
    public function read($id)
    {
        return (string) @file_get_contents("$this->savePath/sess_$id");
    }

    /**
     * @param $id
     * @param $data
     */
    public function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }
}
