<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\MaximoResponse;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class GetTest extends TestCase
{
    public function testGetMethodReturnsMaximoResponseObject(): void
    {
        $this->fakeLogin();
    
        Http::fake();
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->where('personid', 'cabey')
            ->get();
    
        $this->assertInstanceOf(MaximoResponse::class, $response);
    }
}
