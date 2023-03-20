<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\KeyNotFound;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class FilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();

        Http::fake([
            '*' => Http::response(MockResponses::multiRecords()),
        ]);
    }
    
    public function testFilterMethodFindsSpecifiedKeyInResponseAndReturnsIt(): void
    {
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->get();
    
        $this->assertCount(2, $response->filter('member'));
    
        $this->assertInstanceOf(Collection::class, $response->filter('member', true));
    }
    
    public function testFilterMethodThrowsExceptionIfKeyCannotBeFound(): void
    {
        $this->expectException(KeyNotFound::class);
        $this->expectExceptionMessage('The specified key, \'unknown key\' could not be found in the response data.');
        
        MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->get()
            ->filter('unknown key');
    }
}
