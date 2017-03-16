<?php

/**
 * Request
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Http/Request
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /**
     * @return mixed
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * @return mixed
     */
    public function isAjax()
    {
        return $this->isXmlHttpRequest();
    }

    public function start()
    {
        //$a = (parent::createFromGlobals());
        //var_dump($a);
        //var_dump($a->duplicate(['a'=>'11']));
        //return ($a->duplicate(['age'=>'25']));
        return (parent::createFromGlobals());
    }
}
