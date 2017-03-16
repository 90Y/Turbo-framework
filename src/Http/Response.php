<?php

/**
 * Response
 * @version   SVN:$Id: t1.php 5121 2016-11-07 09:05:49Z kakaxi $
 * @package   Turbo/Http/Response
 *
 * @author    xiewei
 * @copyright 2016 clejw
 */

namespace Turbo\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    public function sendJson()
    {
        return 'hello world';
    }
}
