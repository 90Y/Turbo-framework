<?php
namespace Turbo\Http;

use Turbo\Http\Request\Request;
use Turbo\Http\Response\Response;
use Turbo\Pipeline\Pipeline;

class Http
{
    public function __construct()
    {

    }

    public function handle(Request $request)
    {
        var_dump($request);
    }

    public function send()
    {
        var_dump('send');
    }

    public function terminate()
    {
        var_dump('terminate');

    }
    public function request()
    {
        return 'request object';
    }

}
