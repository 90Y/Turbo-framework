<?php
/**
 * Sitecontrol
 *
 * 默认访问控制器 用于用户首页展示数据供应
 *
 * @version   SVN:$Id: SiteControll.php 22779 2016-12-14 09:40:38Z kakaxi $
 * @package   Shop/Sitecontroll
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

use Turbo\Support\Controller\Controller as BaseController;

class SiteControll extends BaseController
{
    /**
     * 校验配置项
     * @var array
     */
    protected $verify = [
        'add' => [
            'get' => [
                'age' => [
                    'must'   => true, //是否必要
                    'notice' => '年龄无效！',
                    //'pass'   => true,//是否忽略校验 优先与 must
                    'notice' => '年龄无效！',

                    //数字
                    'type'   => 'num',

                    //是否空
                    'type'   => 'empty',

                    //值全等  == 25
                    'type'   => 'fixed',
                    'value'  => '25',

                    //数字范围值  前开后闭区间 18-50  18-   -50  18-19
                    //'type'   => 'numLimit',
                    //'value'  => '18-19',

                    //字符串长度 与 numLimit 等同使用
                    //'type'   => 'length',
                    //'value'  => '6-12',

                    //枚举值
                    'type'   => 'select',
                    'value'  => ['15', '23'],

                    //正则
                    'type'   => 'reg',
                    'value'  => '/^([0-9]+)$/i',

                    //callback 回调 支持字符串函数名 传递两个参数 1.选中的值 2.整个提交数据 函数返回 boolean
                    //'type'   => 'func',
                    //'value'  => 'UserService::Verify'
                ],
            ],
            //'post' => [
            //'name'=> [
            //'type'   => 'empty',非空校验
            //'must'   => true,是否必要
            //'notice' => '该项无效',
            //],
            //],
        ],
    ];

    /**
     * @return mixed
     */
    public function add()
    {

		//$sess = $this->app->make(\Turbo\Http\Session\Session::class);
		//$sess->name = 'kakaxi';
		//var_dump($sess);
		//var_dump(Route::$groups);
        $ret = $this->getParams();
        $ret = $this->postParams();
        return $this->okMsg($ret);
        return 'dd';
    }

	public function update()
	{
		return 'update';
	}

    /**
     * @return mixed
     */
    public function index()
    {
        //$log = $this->app->make('log');
        //$log->logIn('test', $this->control.'_'.$this->action);
        //$log->logIn(range(2,4), $this->control.'_'.$this->action);
        //var_dump($log);
        //
        // get & post test
        //$get = $this->getParams('a');
        //$post = $this->postParams('p1', 'not exist!');
        //var_dump($get, $post);
        //exit();
        $page = $this->getParams('page', 1);

        $userSer = $this->service('UserService');
        $result  = $userSer->getuserList($page);

        return $this->okMsg($result);
    }
}
