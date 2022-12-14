<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\MaximoResponse;



test('the create method returns a maximo response object', function() {
    $this->fakeLogin();

    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->create([
            'propertyA' => 'valueA'
        ]);

    $this->assertInstanceOf(MaximoResponse::class, $response);
});
