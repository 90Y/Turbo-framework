<?php

namespace Turbo\Interfaces\Support\Controller;

interface Controller
{
    //service
    public function service($serviceName);

    public function okMsg($data, $msg = '');

    public function errMsg($msg, $errCode);
}
