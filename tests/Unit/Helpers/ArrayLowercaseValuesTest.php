<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\Helpers;

use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class ArrayLowercaseValuesTest extends TestCase
{
    public function testConvertsArrayValuesToLowercase()
    {
        $array = ['THIS_SHOULD_BE_LOWERCASE', 'SO_SHOULD_THIS'];
    
        foreach (array_lowercase_values($array) as $value) {
            $this->assertSame(strtolower($value), $value);
        }
    }
}
