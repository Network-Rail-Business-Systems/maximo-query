<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidResponse;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\Data\MockResponses;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class GetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->fakeLogin();
    }
    
    public function testGetMethodMakesHttpRequest(): void
    {
        Http::fake();
    
        $queryObject = MaximoQuery::withObjectStructure('mxperson')->selectAll();
    
        $url = $queryObject->getUrl();
    
        $queryObject->get();
    
        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url
                && $request->method() === 'GET';
        });
    }
    
    public function testThrowsExceptionIf200ResponseNotReceived(): void
    {
        $this->expectException(InvalidResponse::class);
        
        Http::fake([
            '*' => Http::response(MockResponses::error404(), 404),
        ]);
    
        MaximoQuery::withObjectStructure('sausage')
            ->selectAll()
            ->get();
    }
}
