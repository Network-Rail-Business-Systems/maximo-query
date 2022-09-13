<?php

namespace Networkrailbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class KeyNotFound extends Exception
{

    #[Pure] public static function inResponse($key): self
    {
        return new self("The specified key, '{$key}' could not be found in the response data.");
    }
}
