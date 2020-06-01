<?php

namespace Nrbusinesssystems\MaximoQuery\Exceptions;

use Exception;
use Nrbusinesssystems\MaximoQuery\MaximoQuery;

class InvalidQuery extends Exception
{

    public static function objectTypeNotSet(): self
    {
        return new self("Object type not set! Use the 'withObjectStructure()' or the 'withMaximoBusinessObject()' methods and pass in the relevant data.");
    }


    public static function invalidWhereOperator(array $validOperators = []): self
    {
        return new self("Invalid operator passed to 'where()' method. Please use one of the following: \n" . print_r($validOperators, true));
    }


}
