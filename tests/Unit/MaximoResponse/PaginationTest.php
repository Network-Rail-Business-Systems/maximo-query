<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class PaginationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();
    }

    public function testCanGetNextPageOfPaginatedDataset(): void
    {
        Http::fakeSequence('*')
            ->push(MockResponses::paginationPageOne())
            ->push(MockResponses::paginationPageTwo());
    
        $page1 = MaximoQuery::withObjectStructure('mxperson')
            ->paginate(1)
            ->get(1);
    
        $page2 = $page1->nextPage();
    
        $this->assertStringContainsString('"pagenum":2', $page2->raw());
    }
    
    public function testCanGetPreviousPageOfPaginatedDataset(): void
    {
        Http::fakeSequence('*')
            ->push(MockResponses::paginationPageTwo())
            ->push(MockResponses::paginationPageOne());
    
        $page2 = MaximoQuery::withObjectStructure('mxperson')
            ->paginate(1)
            ->get(2);
    
        $page1 = $page2->prevPage();
    
        $this->assertStringContainsString('"pagenum":1', $page1->raw());
    }
    
    public function testReturnsNullWhenTryingToGetPageOfNonPaginatedDataset(): void
    {
        Http::fake([
            '*' => Http::response(MockResponses::singleRecord()),
        ]);
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->get();
    
        $this->assertNull($response->nextPage());
    }
}
