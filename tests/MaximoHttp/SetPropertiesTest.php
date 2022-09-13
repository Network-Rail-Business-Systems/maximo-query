<?php

use Illuminate\Support\Facades\Http;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();

    Http::fake();
});

test('default properties are set if none are specified', function() {
    MaximoQuery::withObjectStructure('mxperson')
        ->create([
            'propertyA' => 'valueA'
        ]);

    Http::assertSent(function ($request) {
        ray($request->headers());
        return $request->hasHeader('properties', ['_rowstamp,href']);
    });
});

test('the correct properties are set if specified', function() {
    MaximoQuery::withObjectStructure('mxperson')
        ->create(
            ['propertyA' => 'valueA'],
            ['potato', 'po-tay-to', 'po-tah-to']
        );

    Http::assertSent(function ($request) {
        return $request->hasHeader('properties', ['potato,po-tay-to,po-tah-to']);
    });
});
