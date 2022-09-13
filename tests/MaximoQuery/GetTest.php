<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\MaximoResponse;

test('the get method returns a maximo response object', function() {
   $this->fakeLogin();

    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->where('personid', 'cabey')
        ->get();

    $this->assertInstanceOf(MaximoResponse::class, $response);
});
