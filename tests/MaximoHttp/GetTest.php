<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidResponse;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();
});

test('the get method makes a http request', function() {
    Http::fake();

    $queryObject = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll();

    $url = $queryObject->getUrl();

    $queryObject->get();

    Http::assertSent(function ($request) use ($url) {
        return $request->url() === $url
            && $request->method() === 'GET';
    });
});

test('will throw an exception if 200 response is not received', function () {
    Http::fake([
        '*' => Http::response(include(__DIR__ . '/../stubs/responses/error-404.php'), 404),
    ]);

    MaximoQuery::withObjectStructure('sausage')
        ->selectAll()
        ->get();

})->expectException(InvalidResponse::class);
