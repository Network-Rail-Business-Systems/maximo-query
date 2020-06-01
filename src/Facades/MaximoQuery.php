<?php

namespace Nrbusinesssystems\MaximoQuery\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \Nrbusinesssystems\MaximoQuery\MaximoQuery withObjectStructure(string $objectStructure)
 * @method static \Nrbusinesssystems\MaximoQuery\MaximoQuery withBusinessObject(string $businessObject)
 *
 */
class MaximoQuery extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'maximo-query';
    }
}
