<?php

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

test('raw method returns the raw json response', function() {
    $this->fakeLogin();

    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/multi-records.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get()
        ->raw();

    $this->assertInstanceOf(Response::class, $response);
});
