<?php
/**
 * Controller
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Support/Controller
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Support\Controller;

use Turbo\Validation\Validation;
use Turbo\Exception\TurboException;
use Turbo\Interfaces\Support\Controller\Controller as iController;

class Controller implements iController
{
    /**
     * @var mixed
     */
    protected $action;

    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $control;

    /**
     * @var mixed
     */
    protected $method;

    /**
     * @var mixed
     */
    protected $params;

    /**
     * @var mixed
     */
    protected $request = null;

    /**
     * @var mixed
     */
    protected $uri;

    /**
     * @var mixed
     */
    protected $verify = [];

    /**
     * @var array
     */
    protected $verifyLimit = [
        'get'  => 'query',
        'post' => 'request',
    ];

    /**
     * @param $app
     * @param $controller
     * @param $action
     */
    public function __construct($app, $controller, $action, $params)
    {
        $this->app     = $app;
        $this->control = $controller;
        $this->action  = $action;
        $this->params  = $params;

        $this->request = ($this->app->make(\Turbo\Http\Request::class));
        $this->uri     = $this->request->getPathInfo();
        $this->method  = $this->request->getMethod();

        // init function
        $this->init();

        //verify
        $this->verify();
    }

    /**
     * @param  $msg
     * @param  $errCode
     * @return mixed
     */
    public function errMsg($errCode, $data = [])
    {
        $msg = 'errCode translation';
        $ret = [
            'code' => $errCode,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($ret);
    }

    /**
     * @param  $key
     * @param  null    $default
     * @return mixed
     */
    public function getParams($key = null, $default = null)
    {
        return $this->params($key, $default, 'query');
    }

    /**
     * @param  $msg
     * @return mixed
     */
    public function okMsg($data, $msg = 'success')
    {
        $ret = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($ret);
    }

    /**
     * @param  $key
     * @param  null    $default
     * @return mixed
     */
    public function postParams($key = null, $default = null)
    {
        return $this->params($key, $default, 'request');
    }

    /**
     * @param $serviceName
     */
    public function service($serviceName)
    {
        return (new $serviceName);
    }

    /**
     * initial function
     */
    protected function init() {}

    /**
     * @param $data
     */
    protected function json($data)
    {
        return json_encode($data);
    }

    /**
     * @param  $key
     * @param  null    $default
     * @param  null    $method
     * @return mixed
     */
    protected function params($key = null, $default = null, $method = 'query')
    {
        $paramObj = $this->request->$method;
        if ($key === null) {
            return $paramObj->all();
        }

        return $paramObj->get($key) ?: $default;
    }

    protected function verify()
    {
        $result = [];
        //verify object
        $valid = new Validation();
        foreach ($this->verifyLimit as $method => $way) {
            if (!isset($this->verify[$this->action][$method]) || empty($verify = $this->verify[$this->action][$method])) {
                continue;
            }

            $data   = $this->params(null, null, $way);
            $verify = $valid->getResult($verify, $data, '[' . $method . '] - ');
            if ($verify) {
                $result[$method] = $verify;
            }
        }
        if ($result) {
            throw new TurboException($this->errMsg('100', $result));
        }

        return false;
    }
}
