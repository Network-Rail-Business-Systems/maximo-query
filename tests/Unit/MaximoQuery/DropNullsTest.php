<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class DropNullsTest extends TestCase
{
    public function testDoesNotDropNullValuesByDefault(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->getUrl();
    
        $this->assertStringContainsString('_dropnulls=0', $url);
    }
    
    public function testUrlDoesNotContainDropnullsWhenFilterNullValuesMethodIsChained(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->filterNullValues()
            ->getUrl();
    
        $this->assertStringNotContainsString('_dropnulls=0', $url);
    }
}
