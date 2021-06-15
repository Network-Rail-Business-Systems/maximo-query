<?php

use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;
use Nrbusinesssystems\MaximoQuery\MaximoResponse;

test('the get method returns a maximo response object', function() {
   $this->fakeLogin();

    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->where('personid', 'cabey')
        ->get();

    $this->assertInstanceOf(MaximoResponse::class, $response);
});
