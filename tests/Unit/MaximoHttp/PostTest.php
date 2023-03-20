<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidResponse;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeLogin();
    }
    
    public function testPostMethodMakesHttpRequest(): void
    {
        Http::fake();
    
        $response = MaximoQuery::withObjectStructure('mxperson')
            ->create([
                'propertyA' => 'valueA'
            ]);
    
        Http::assertSent(function ($request) use ($response) {
            return $request->url() === $response->getUrl()
                && $request->method() === 'POST';
        });
    }
    
    public function testThrowsExceptionIf200ResponseNotReceived(): void
    {
        $this->expectException(InvalidResponse::class);
        
        Http::fake([
            '*' => Http::response(MockResponses::error404(), 404),
        ]);
    
        MaximoQuery::withObjectStructure('mxperson')
            ->create([
                'propertyA' => 'valueA'
            ]);
    }
}
