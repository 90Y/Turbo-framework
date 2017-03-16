<?php

/**
 * user service
 * @version   SVN:$Id: UserService.php 22073 2016-12-13 11:08:11Z kakaxi $
 * @package   Turbo/RouteDispatch
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

use Turbo\Support\Service\Service as BaseService;

class UserService extends BaseService
{
    /**
     * @param $value
     * @param $data
     */
    public static function Verify($value, $data)
    {
        var_dump(func_get_args());

        return true;
    }

    /**
     * unction
     *
     * @param  numeric $page the page
     * @return array
     */
    public function getUserList($page)
    {
        $userModel = $this->model('UserModel');

        return $userModel->getUserData($page);
    }
}
