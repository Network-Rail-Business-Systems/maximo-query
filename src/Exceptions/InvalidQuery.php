<?php

namespace Networkrailbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class InvalidQuery extends Exception
{

    #[Pure] public static function objectTypeNotSet(): self
    {
        return new self("Object type not set! Use the 'withObjectStructure()' or the 'withMaximoBusinessObject()' methods and pass in the relevant data.");
    }


    public static function invalidWhereOperator(array $validOperators = []): self
    {
        return new self("Invalid operator passed to 'where()' method. Please use one of the following: \n" . print_r($validOperators, true));
    }

    #[Pure] public static function noWhereClause(): self
    {
        return new self('No where clause has been set. Please filter your query so that a single resource is updated');
    }




}
