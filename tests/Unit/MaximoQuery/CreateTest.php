<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\MaximoResponse;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class CreateTest extends TestCase
{
    public function testCreateMethodReturnsMaximoResponseObject(): void
    {
        $this->fakeLogin();
    
        Http::fake();
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->create([
                'propertyA' => 'valueA'
            ]);
    
        $this->assertInstanceOf(MaximoResponse::class, $response);
    }
}
