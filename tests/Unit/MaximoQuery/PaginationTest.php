<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class PaginationTest extends TestCase
{
    public function testHasDefaultPagination(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->getUrl();
    
        $this->assertStringContainsString('oslc.pageSize=1000', $url);
    }
    
    public function testPaginationCanBeSetToSpecificValue(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->paginate(100)
            ->getUrl();
    
        $this->assertStringContainsString('oslc.pageSize=100', $url);
    }
    
    public function testPaginationCanBeDisabled(): void
    {
        $url = MaximoQuery::withObjectStructure('mxperson')
            ->withoutPagination()
            ->getUrl();
    
        $this->assertStringNotContainsString('oslc.pageSize', $url);
    }
}
