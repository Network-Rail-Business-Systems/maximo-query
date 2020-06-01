<?php

namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;

class KeyNotFound extends Exception
{

    public static function inResponse($key): self
    {
        return new self("The specified key, '{$key}' could not be found in the response data.");
    }
}
