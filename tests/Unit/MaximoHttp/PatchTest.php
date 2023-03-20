<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidResponse;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class PatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();
    }
    
    public function testThePatchMethodMakesHttpRequest(): void
    {
        Http::fake([
            '*/oslc/os/trim*' => Http::response(MockResponses::singleRecord()),
            '*/oslc/os/mxperson*' => Http::response(MockResponses::updateNoProperties()),
        ]);
    
        MaximoQuery::withObjectStructure('trim')
            ->where('sausage', 'eggs')
            ->update([]);
    
        Http::assertSent(function ($request) {
            return $request->url() === 'http://localhost/maximo/oslc/os/mxperson/_Q0FCRVk-'
                && $request->method() === 'POST'
                && $request->hasHeader('properties', ['_rowstamp,href'])
                && $request->hasHeader('x-method-override', ['PATCH']);
        });
    }
    
    public function testWillThrowExceptionIf200ResponseIsNotReceived(): void
    {
        $this->expectException(InvalidResponse::class);
        
        $this->fakeLogin();
    
        Http::fake([
            '*' => Http::response(MockResponses::error404(), 404),
        ]);
    
        MaximoQuery::withObjectStructure('trim')
            ->where('sausage', 'eggs')
            ->update([]);
    
    }
}
