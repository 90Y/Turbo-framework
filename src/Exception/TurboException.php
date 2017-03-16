<?php

/**
 * 框架底层Exception
 *
 * @version   svn:$id: http.php 4533 2016-11-04 09:39:35z kakaxi $
 * @package   turbo/exception
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Exception;

use Exception;

class TurboException extends Exception
{
    /**
     * @param Exception $e
     */
    public function exitException()
    {
        //$str = '<h1 style="color:red">Exception:</h1>
        //Msg => ' . $this->getMessage() . '<br />
        //Code => ' . $this->getCode() . '<br />
        //Line => ' . $this->getLine() . '<br />
        //File => ' . $this->getFile();
        return $this->getMessage();
    }
}
