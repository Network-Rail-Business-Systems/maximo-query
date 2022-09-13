<?php

use Illuminate\Support\Facades\Http;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('returns the collection count if in response', function() {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/collection-count.php'),
    ]);

    $count = MaximoQuery::withObjectStructure('trim')
        ->where('nrassignedto', 'ATHOMP18')
        ->withCount()
        ->get()
        ->getCount();

    $this->assertSame(349, $count);
});

it('returns 0 if collection count is not in response', function() {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/multi-records.php'),
    ]);

    $count = MaximoQuery::withObjectStructure('mxperson')
        ->get()
        ->getCount();

    $this->assertSame(0, $count);
});
