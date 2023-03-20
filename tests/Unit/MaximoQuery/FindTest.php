<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoQuery;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class FindTest extends TestCase
{
    public function testFindMethodReturnsSingleRecordAsArray(): void
    {
        $this->fakeLogin();
    
        Http::fake([
            '*/oslc/os/mxperson/1191*' => Http::response(MockResponses::singleRecord()),
        ]);
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->find(1191);
    
        $this->assertIsArray($response);
    }
}
