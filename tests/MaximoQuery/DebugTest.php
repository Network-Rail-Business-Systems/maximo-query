<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\Debug;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

test('the get method returns a maximo response object', function() {
    $this->fakeLogin();

    $instance = MaximoQuery::withObjectStructure('mxperson');

    $requestUrl = $instance->getUrl();

    $instance
        ->debug()
        ->get();

    Http::assertNotSent(fn (Request $request) => $request->url() === $requestUrl);

});
