<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class CountTest extends TestCase
{
    public function testCountMethodReturnsAnInteger(): void
    {
        $this->fakeLogin();
    
        Http::fake([
            '*count=1*' => Http::response(["totalCount" => 2345]),
        ]);
    
        $count = MaximoQuery::withObjectStructure('mxperson')
            ->count();
    
        $this->assertIsInt($count);
        $this->assertSame(2345, $count);
    }
}
