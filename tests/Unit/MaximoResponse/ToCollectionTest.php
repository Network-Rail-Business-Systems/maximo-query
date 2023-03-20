<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoResponse;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class ToCollectionTest extends TestCase
{
    public function testCanReturnResponseAsCollection(): void
    {
        Http::fake();
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->get()
            ->toCollection();
    
        $this->assertInstanceOf(Collection::class, $response);
    }
}
