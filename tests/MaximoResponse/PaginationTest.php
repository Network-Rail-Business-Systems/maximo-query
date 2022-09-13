<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(fn () => $this->fakeLogin());

it('can get next page of a paginated dataset', function () {
    Http::fakeSequence('*')
        ->push(require __DIR__ . '/../stubs/responses/pagination-page-1.php')
        ->push(require __DIR__ . '/../stubs/responses/pagination-page-2.php');

    $page1 = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(1)
        ->get(1);

    $page2 = $page1->nextPage();

    $this->assertStringContainsString('"pagenum":2', $page2->raw());
});



it('can get previous page of a paginated dataset', function () {
    Http::fakeSequence('*')
        ->push(require __DIR__ . '/../stubs/responses/pagination-page-2.php')
        ->push(require __DIR__ . '/../stubs/responses/pagination-page-1.php');

    $page2 = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(1)
        ->get(2);

    $page1 = $page2->prevPage();

    $this->assertStringContainsString('"pagenum":1', $page1->raw());
});



it('returns null when trying to get the page of a non paginated dataset', function () {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/single-record.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->get();

    $this->assertNull($response->nextPage());
});
