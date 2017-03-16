<?php

//namespace \App\Controller;

use Turbo\Support\Controller\Controller as BaseController;

class SiteControll extends BaseController
{
    public function index()
    {
        // get & post test
        //$get = $this->getParams('a');
        //$post = $this->postParams('p1', 'not exist!');
        //var_dump($get, $post);
        //exit();
        $page = $this->getParams('page');

        $userSer = $this->service('UserService');
        $result = $userSer->getuserList($page);
        return $this->okMsg($result);
    }
}
