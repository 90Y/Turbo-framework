<?php

/**
 * UserModel
 * @version   SVN:$Id: UserModel.php 22073 2016-12-13 11:08:11Z kakaxi $
 * @package   Turbo/RouteDispatch
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

use Turbo\Support\Model\Model;

class UserModel extends Model
{
    //protected $dbTable = 'user';
    //protected $primaryKey = 'id';
    /**
     * @var int
     */
    public static $pageLimit = 2;

    //by default: 20 ;common set in Model not set
    //public static $totalPages;//不需要赋值

    /**
     * @var array
     */
    protected $dbFields = [
        'name'     => ['text', 'required'],
        'password' => ['text', 'required'],
    ];

    /**
     * @param $page
     * @return mixed
     */
    public function getUserData($page)
    {
        // doc address: https://github.com/joshcam/PHP-MySQLi-Database-Class/blob/master/dbObject.md
        $users = self::table('user')->orderBy('id', 'desc')->paginate($page); //分页结果是 二维数组，中间不是 对象

        return ($users);
        //$users = \UserModel::get();
        //object 转换为数组
        $userData = array_map(function ($user) {
            return $user->data;
        }, $users);

        return ($userData);
        //$users = self::table('user')->byId(1); //object
        //var_dump($users);
        //$userJson = $users->toJson(); //  $users = self::JsonBuilder()->byId(1);
        //$userArray = $users->toArray(); // $users = self::ArrayBuilder()->byId(1);
        //var_dump($users);
        //var_dump($userJson, $userArray);
        //var_dump(get_class_methods($this));

        //insert
        //
        //$user = self::table('user');
        //$user = new \UserModel;
        //$user->name = 'demo1';
        //$user->email= 'demo1@demo.com';
        //$user->password = 'demo';
        //$id = $user->save();
        //var_dump($id);

        //使用数组
        //$data = [
        //'name'=>'demo2',
        //'password'=>'demo2',
        //'email'=>'demo2',
        //];
        //$user = new \UserModel($data);
        //$id = $user->save();
        //var_dump($id);
        // get error
        //print_r($user->errors);
        //echo $db->getLastQuery();
        //echo $db->getLastError();

        // select
        //$users = \UserModel::get();w;
        //$users = self::table('user')->orderBy("id", "desc")->get();

        //使用分页
        //每页数量 由该类静态属性 pageLimit 控制 总的分页数存放在 静态属性 totalPages中
        //$users = self::table('user')->orderBy("id", "desc")->paginate(1);//分页结果是 二维数组，中间不是 对象
        //var_dump($users);exit();
        //
        //$count = self::table('user')->orderBy("id", "desc")->count();//总数
        //echo 'each page number is :'. static::$pageLimit . ' and totalpages is:'. static::$totalPages;

        //primary key  //主键 可通过 $primaryKey  设置默认id
        //$users = \UserModel::byId(1);
        //$users = self::table('user')->byId(1); //object
        //var_dump($users->data);

        //where
        //$users = \UserModel::where('id', '1')->where('name', 'kakaxi')->get(); // array
        //var_dump($users);

        //update
        //
        //notice 需要修改的字段放在 dbFields 属性中

        //$user = \UserModel::byId(1);
        //var_dump($user->data);
        //$user->password = 'demo2';
        //$ret = $user->save();
        //var_dump($ret);

        // 或者
        //$updata = [
        //'password' => 'newdemo',
        //];
        //$user = \UserModel::byId(1);
        //var_dump($user->data);
        //$ret = $user->save($updata);
        //var_dump($ret);

        //delette
        //
        //$user = \UserModel::byId(11);
        //$user->delete();
        //
        //
        //其它 join between belongs hasMany 参看文档
    }
}
