<?php

use Illuminate\Support\Facades\Http;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;
use Networkrailbusinesssystems\MaximoQuery\MaximoResponse;

test('the get method returns a maximo response object', function() {
   $this->fakeLogin();

    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->where('personid', 'cabey')
        ->get();

    $this->assertInstanceOf(MaximoResponse::class, $response);
});
