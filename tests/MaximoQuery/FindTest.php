<?php

use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

test('the find method returns a single record as an array', function() {
    $this->fakeLogin();

    Http::fake([
        '*/oslc/os/mxperson/1191*' => Http::response(require __DIR__ . '/../stubs/responses/single-record.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->find(1191);

    $this->assertIsArray($response);

    $this->assertArrayHasKey('personuid', $response);
});
