<?php
/**
 * Config
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Support/Config
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */
namespace Turbo\Support\Config;

use Turbo\Interfaces\Operation\Application;

class Config
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
