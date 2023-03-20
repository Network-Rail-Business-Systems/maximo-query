<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\Helpers;

use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class ArrayLowercaseKeysTest extends TestCase
{
    public function testConvertsArrayKeysToLowercase(): void
    {
        $array = [
            'THIS_SHOULD_BE_LOWERCASE' => 'some value',
            'SO_SHOULD_THIS' => 'another value',
        ];
    
        foreach (array_keys(array_lowercase_keys($array)) as $key) {
            $this->assertSame(strtolower($key), $key);
        }
    }
}
    
