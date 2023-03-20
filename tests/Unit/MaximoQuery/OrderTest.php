<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class OrderTest extends TestCase
{
    public function testCanOrderBySingleColumn(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->orderBy('column1', 'desc')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.orderBy=-column1', $url);
    }
    
    public function testCanOrderByMultipleColumns(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->orderBy([
                ['column1', 'desc'],
                ['column2', 'asc']
            ])
            ->getUrl();
    
        $this->assertStringContainsString('oslc.orderBy=-column1,+column2', $url);
    }
}
