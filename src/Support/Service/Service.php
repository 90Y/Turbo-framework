<?php
/**
 * service
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Support/Service
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Support\Service;

class Service
{
    public function init() {}

    /**
     * @param $modelName
     */
    public function model($modelName)
    {
        return (new $modelName);
    }

    /**
     * @param $serviceName
     */
    public function sibling($serviceName)
    {
        return (new $serviceName);
    }
}
