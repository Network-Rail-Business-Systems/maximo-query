<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class RawTest extends TestCase
{
    public function testRawMethodReturnsRawJsonResponse(): void
    {
        $this->fakeLogin();
    
        Http::fake([
            '*' => Http::response(MockResponses::multiRecords()),
        ]);
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->get()
            ->raw();
    
        $this->assertInstanceOf(Response::class, $response);
    }
}
