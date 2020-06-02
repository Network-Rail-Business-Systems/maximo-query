<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

afterEach(function () {
    Cache::forget(config('maximo-query.cookie_cache_key'));
});


it('throws an exception if object type is not set', function() {
    $this->expectException(InvalidQuery::class);
    $this->expectExceptionMessage("Object type not set! Use the 'withObjectStructure()' or the 'withMaximoBusinessObject()' methods and pass in the relevant data.");

    MaximoQuery::getUrl();
});


test('withObjectStructure method returns the correct query string', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    assertIsString($url);

    assertStringContainsString('os/mxperson', $url);
});



test('withBusinessObject method returns the correct query string', function() {
    $url = MaximoQuery::withBusinessObject('person')
        ->getUrl();

    assertStringContainsString('mbo/person', $url);
});



it('selects no columns by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    assertStringNotContainsString('oslc.select', $url);
});



test('selectAll method returns the correct query string', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->getUrl();

    assertStringContainsString('oslc.select=*', $url);
});



test('specific columns can be requested', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->select(['column1', 'column2', 'column3'])
        ->getUrl();

    assertStringContainsString('oslc.select=column1,column2,column3', $url);
});



it('has a default pagination', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    assertStringContainsString('oslc.pageSize=1000', $url);
});



test('pagination can be set to a specific value', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(100)
        ->getUrl();

    assertStringContainsString('oslc.pageSize=100', $url);
});



test('pagination can be disabled', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->withoutPagination()
        ->getUrl();

    assertStringNotContainsString('oslc.pageSize', $url);
});



it('will not request collection count by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    assertStringNotContainsString('_collectioncount', $url);
});



test('collection count can be enabled', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->withCount()
        ->getUrl();

    assertStringContainsString('collectioncount=1', $url);
});



it('does not drop null values by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    assertStringContainsString('_dropnulls=0', $url);
});



it('can request the response to not return null values', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->filterNullValues()
        ->getUrl();

    assertStringContainsString('_dropnulls=1', $url);
});



it('can order by a single column', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->orderBy('column1', 'desc')
        ->getUrl();

    assertStringContainsString('oslc.orderBy=-column1', $url);
});



it('can order by a multiple columns', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->orderBy([
            ['column1', 'desc'],
            ['column2', 'asc']
        ])
        ->getUrl();

    assertStringContainsString('oslc.orderBy=-column1,+column2', $url);
});



test('passing an invalid operator to where throws an exception', function(string $operator, bool $expectException) {
    if ($expectException) {
        $this->expectException(InvalidQuery::class);
        $this->expectExceptionMessage("Invalid operator passed to 'where()' method. Please use one of the following: \n" . print_r(['=', '>=', '>', '<', '⇐', '!='], true));
    }

    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', $operator, 'some value')
        ->getUrl();

    assertStringContainsString("oslc.where=column1{$operator}\"some value\"", $url);
})->with([
    ['=', false],
    ['>=', false],
    ['>', false],
    ['<', false],
    ['⇐', false],
    ['!=', false],
    ['#', true],
]);



test('where method defaults to equals if no operator is passed', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', 'some value')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="some value"', $url);
});



test('numeric values passed to where method are not quoted', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', 100)
        ->getUrl();

    assertStringContainsString('oslc.where=column1=100', $url);
});



it('can add where in clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereIn('column1', ['tom', 'dick', 'harry'])
        ->getUrl();

    assertStringContainsString('oslc.where=column1 in ["tom","dick","harry"]', $url);
});



it('can add where not in clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNotIn('column1', ['tom', 'dick', 'harry'])
        ->getUrl();

    assertStringContainsString('oslc.where=column1!="[tom,dick,harry]"', $url);
});


it('can add where starts with clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereStartsWith('column1', 'some value')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="some value%"', $url);
});



it('can add where ends with clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereEndsWith('column1', 'some value')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="%some value"', $url);
});



it('can add where like clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereLike('column1', 'some value')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="%some value%"', $url);
});



it('can add where null clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNull('column1')
        ->getUrl();

    assertStringContainsString('oslc.where=column1!="*"', $url);
});



it('can add where not null clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNotNull('column1')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="*"', $url);
});



test('count method returns an integer', function() {
    $this->fakeLogin();

    Http::fake([
        '*/oslc/os/mxperson?count=1' => Http::response(["totalCount" => 2345]),
    ]);

    $count = MaximoQuery::withObjectStructure('mxperson')
        ->count();

    assertIsInt($count);
});



test('where methods can be chained', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', 'some value')
        ->where('column2', 'another value')
        ->getUrl();

    assertStringContainsString('oslc.where=column1="some value" and column2="another value"', $url);
});



test('the find method returns a single record as an array', function() {
    $this->fakeLogin();

    Http::fake([
        '*/oslc/os/mxperson/1191' => Http::response(require __DIR__ . '/stubs/responses/single-record.php'),
    ]);

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->find(1191);

    assertIsArray($response);

    assertArrayHasKey('spi:personuid', $response);
});
