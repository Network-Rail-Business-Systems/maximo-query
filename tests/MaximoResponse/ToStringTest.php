<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

test('tostring method returns the response as a string', function() {
    $this->fakeLogin();

    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/single-record.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get()
        ->__toString();

    $this->assertIsString($response);
});
