<?php


namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use Illuminate\Http\Client\Request;

class Debug extends Exception
{
    public static function dumpRequest(Request $request)
    {
        dump($request);

        return new self();
    }
}
