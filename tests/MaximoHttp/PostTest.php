<?php

use Illuminate\Support\Facades\Http;
use Networkrailbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();
});

test('the post method makes a http request', function() {
    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->create([
            'propertyA' => 'valueA'
        ]);

    Http::assertSent(function ($request) use ($response) {
        return $request->url() === $response->getUrl()
            && $request->method() === 'POST';
    });
});

test('will throw an exception if 200 response is not received', function () {
    Http::fake([
        '*' => Http::response(include(__DIR__ . '/../stubs/responses/error-404.php'), 404),
    ]);

    MaximoQuery::withObjectStructure('mxperson')
        ->create([
            'propertyA' => 'valueA'
        ]);

})->expectException(InvalidResponse::class);

