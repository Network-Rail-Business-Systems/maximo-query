<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class GetUrlTest extends TestCase
{
    public function testReturnsUrl(): void
    {
        Http::fake();
    
        $instance = MaximoQuery::withObjectStructure('mxperson');
        $url = $instance->getUrl();
    
        $response = $instance->get();
    
        $this->assertSame($url, $response->getUrl());
    }
}
