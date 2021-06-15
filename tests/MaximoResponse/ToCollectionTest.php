<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('can return the response as a collection', function () {
    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->get()
        ->toCollection();

    $this->assertInstanceOf(Collection::class, $response);
});
