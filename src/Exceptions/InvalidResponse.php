<?php

namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use JetBrains\PhpStorm\Pure;

class InvalidResponse extends Exception
{

    #[Pure] public static function notSuccessful(Response $response): self
    {
        ['Error' => $error] = $response;

        return new self(message: $error['message'], code: $error['statusCode']);
    }

    #[Pure] public static function resourceNotFound(): self
    {
        return new self(message: 'A resource could not be found. Please try different parameters.', code: 404);
    }

    #[Pure] public static function multipleResourcesFound(): self
    {
        return new self(
            message: 'Your query was ambiguous and multiple resources were found. Updates can only be performed on single resources.',
            code: 403
        );
    }
}
