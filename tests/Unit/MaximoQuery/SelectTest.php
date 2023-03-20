<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class SelectTest extends TestCase
{
    public function testSelectsNoColumnsByDefault(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->getUrl();
    
        $this->assertStringNotContainsString('oslc.select', $url);
    }
    
    public function testSelectAllMethodReturnsCorrectQueryStringPartial(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->getUrl();
    
        $this->assertStringContainsString('oslc.select=*', $url);
    }
    
    public function testSpecificColumnsCanBeRequested(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->select(['column1', 'column2', 'column3'])
            ->getUrl();
    
        $this->assertStringContainsString('oslc.select=column1,column2,column3', $url);
    }
}
