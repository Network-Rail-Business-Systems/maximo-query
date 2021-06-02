<?php

namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;
use Nrbusinesssystems\MaximoQuery\MaximoQuery;

class InvalidResponse extends Exception
{

    public static function notOk(Response $response): self
    {
        dump($response);
        return new self("{$response->getStatusCode()}: {$response->getReasonPhrase()}");
    }
}
