<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class GetCountTest extends TestCase
{
    public function testReturnsCollectionCountIfResponse(): void
    {
        Http::fake([
            '*' => Http::response(MockResponses::collectionCount()),
        ]);
    
        $count = MaximoQuery::withObjectStructure('trim')
            ->where('nrassignedto', 'ATHOMP18')
            ->withCount()
            ->get()
            ->getCount();
    
        $this->assertSame(349, $count);
    }
    
    public function testReturns0IfCollectionCountNotResponse(): void
    {
        Http::fake([
            '*' => Http::response(MockResponses::multiRecords()),
        ]);
    
        $count = MaximoQuery::withObjectStructure('mxperson')
            ->get()
            ->getCount();
    
        $this->assertSame(0, $count);
    }
}
