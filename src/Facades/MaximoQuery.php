<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \NetworkRailBusinessSystems\MaximoQuery\MaximoQuery withObjectStructure(string $objectStructure)
 * @method static \NetworkRailBusinessSystems\MaximoQuery\MaximoQuery withBusinessObject(string $businessObject)
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
