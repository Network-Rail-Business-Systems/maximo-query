<?php


namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use JetBrains\PhpStorm\Pure;

class CouldNotAuthenticate extends Exception
{

    public static function fromResponse(Response $response): self
    {
        return new self($response->json(), $response->status());
    }

    #[Pure] public static function credentialsNotSetInConfig(): self
    {
        return new self("The 'username' and/or 'password' has not be set in the config file!");
    }


}
