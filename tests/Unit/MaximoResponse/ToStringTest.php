<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class ToStringTest extends TestCase
{
    public function testToStringMethodReturnsResponseAsString(): void
    {
        $this->fakeLogin();
    
        Http::fake([
            '*' => Http::response(MockResponses::singleRecord()),
        ]);
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->selectAll()
            ->get()
            ->__toString();
    
        $this->assertIsString($response);
    }
}
