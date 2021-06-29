<?php


use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();
});

test('the patch method makes a http request', function() {
    Http::fake([
        '*/oslc/os/trim*' => Http::response(include(__DIR__ . '/../stubs/responses/single-record.php')),
        '*/oslc/os/mxperson*' => Http::response(include(__DIR__ . '/../stubs/responses/update-no-properties.php')),
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('sausage','eggs')
        ->update([]);

    Http::assertSent(function ($request) {
        return $request->url() === 'http://localhost/maximo/oslc/os/mxperson/_Q0FCRVk-'
            && $request->method() === 'POST'
            && $request->hasHeader('properties', ['_rowstamp,href'])
            && $request->hasHeader('x-method-override', ['PATCH']);
    });
});

test('will throw an exception if 200 response is not received', function () {
    $this->fakeLogin();

    Http::fake([
        '*' => Http::response(include(__DIR__ . '/../stubs/responses/error-404.php'), 404),
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('sausage','eggs')
        ->update([]);

})->expectException(InvalidResponse::class);
