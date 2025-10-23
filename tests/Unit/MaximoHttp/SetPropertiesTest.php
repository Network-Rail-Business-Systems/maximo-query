<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class SetPropertiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();

        Http::fake();
    }
    
    public function testDefaultPropertiesSetIfNoneSpecified(): void
    {
        MaximoQuery::withObjectStructure('mxperson')
            ->create([
                'propertyA' => 'valueA'
            ]);
    
        Http::assertSent(function ($request) {
            return $request->hasHeader('properties', ['_rowstamp,href']);
        });
    }
    
    public function testCorrectPropertiesSetIfSpecified(): void
    {
        MaximoQuery::withObjectStructure('mxperson')
            ->create(
                ['propertyA' => 'valueA'],
                ['potato', 'po-tay-to', 'po-tah-to']
            );
    
        Http::assertSent(function ($request) {
            return $request->hasHeader('properties', ['potato,po-tay-to,po-tah-to']);
        });
    }
}
