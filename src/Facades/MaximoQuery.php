<?php

namespace Networkrailbusinesssystems\MaximoQuery\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \Networkrailbusinesssystems\MaximoQuery\MaximoQuery withObjectStructure(string $objectStructure)
 * @method static \Networkrailbusinesssystems\MaximoQuery\MaximoQuery withBusinessObject(string $businessObject)
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
