<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\KeyNotFound;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

beforeEach(function () {
    $this->fakeLogin();
});

test('filter method finds the specified key in response and returns it', function () {
    Http::fake([
        '*/oslc/os/mxperson?oslc.select=*&oslc.pageSize=5&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/multi-records.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->paginate(5)
        ->get();

    $this->assertCount(4, $response->toArray());

    $this->assertCount(5, $response->filter('rdfs:member'));

    $this->assertInstanceOf(Collection::class, $response->filter('rdfs:member', true));
});



test('filter method throws an exception if the key cannot be found', function () {
    $this->expectException(KeyNotFound::class);
    $this->expectExceptionMessage("The specified key, 'unknown key' could not be found in the response data.");

    Http::fake([
        '*/oslc/os/mxperson?oslc.select=*&oslc.pageSize=5&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/multi-records.php'),
    ]);

    MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->paginate(5)
        ->get()
        ->filter('unknown key');
});



test('raw method returns the raw json response', function() {
    Http::fake([
        '*/oslc/os/*' => Http::response(require __DIR__ . '/stubs/responses/multi-records.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get()
        ->raw();

    $this->assertJson($response);
});



it('can return the response as a collection', function () {
    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->get()
        ->toCollection();

    $this->assertInstanceOf(Collection::class, $response);
});



it('can get next page of a paginated dataset', function () {
    Http::fake([
        '*/oslc/os/mxperson?pageno=2&oslc.pageSize=1&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/pagination-page-2.php'),
        '*/oslc/os/mxperson?pageno=1&oslc.pageSize=1&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/pagination-page-1.php'),
    ]);

    $page1 = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(1)
        ->get(1);

    $page2 = $page1->nextPage();

    $this->assertStringContainsString('"pagenum":2', $page2->raw());

});



it('can get previous page of a paginated dataset', function () {
    Http::fake([
        '*/oslc/os/mxperson?pageno=2&oslc.pageSize=1&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/pagination-page-2.php'),
        '*/oslc/os/mxperson?pageno=1&oslc.pageSize=1&_dropnulls=0' => Http::response(require __DIR__ . '/stubs/responses/pagination-page-1.php'),
    ]);

    $page2 = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(1)
        ->get(2);

    $page1 = $page2->prevPage();

    $this->assertStringContainsString('"pagenum":1', $page1->raw());
});



it('returns null when trying to get the page of a non paginated dataset', function () {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/stubs/responses/single-record.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->get();

    $this->assertNull($response->nextPage());
});

