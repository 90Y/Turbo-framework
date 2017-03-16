<?php

/**
 * session
 *
 * @version   svn:$id: http.php 4533 2016-11-04 09:39:35z kakaxi $
 * @package   turbo/Http/Session
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Http\Session;

use Turbo\Interfaces\Operation\Application;
use Turbo\Interfaces\Http\Session\Session as iSession;

class Session implements iSession
{
    /**
     * @var mixed
     */
    protected $app;

	protected $sess = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle()
    {
        //$sessionType = config('session', 'config')['type'];
        //$handName = '\\' .__NAMESPACE__.'\\'. $sessionType. 'Handler';
        //$handler = new $handName($this->app);
        //session_set_save_handler($handler, true);
        session_start();
        //phpinfo();
        $this->app->instance(\Turbo\Http\Session\Session::class, $this);
    }

	public function __set($key, $value)
	{
		$_SESSION[$key] = $value;
		//$this->sess[$key] = $value;
	}

	public function __get($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}
}
