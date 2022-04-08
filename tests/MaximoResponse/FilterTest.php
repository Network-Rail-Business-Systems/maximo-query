<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\KeyNotFound;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();

    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/multi-records.php'),
    ]);
});

test('filter method finds the specified key in response and returns it', function () {
    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get();

    $this->assertCount(2, $response->filter('member'));

    $this->assertInstanceOf(Collection::class, $response->filter('member', true));
});


test('filter method throws an exception if the key cannot be found', function () {
    MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get()
        ->filter('unknown key');
})->throws(
    exception: KeyNotFound::class,
    exceptionMessage: "The specified key, 'unknown key' could not be found in the response data."
);
