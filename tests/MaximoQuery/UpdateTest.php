<?php

use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;
use Nrbusinesssystems\MaximoQuery\MaximoResponse;

it('will throw an exception if there is no where clause', function() {
    MaximoQuery::withObjectStructure('trim')
        ->update([]);
})->throws(
    exception: InvalidQuery::class,
    exceptionMessage: 'No where clause has been set. Please filter your query so that a single resource is updated'
);


it('will throw an exception if the where clause returns multiple records', function() {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/multi-records.php')
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('sausage','eggs')
        ->update([]);
})->throws(
    exception: InvalidResponse::class,
    exceptionMessage: 'Your query was ambiguous and multiple resources were found. Updates can only be performed on single resources.'
);


it('will throw an exception if no resource is found', function() {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/no-results.php')
    ]);

    MaximoQuery::withObjectStructure('trim')
        ->where('sausage','eggs')
        ->update([]);
})->throws(
    exception: InvalidResponse::class,
    exceptionMessage: 'A resource could not be found. Please try different parameters.'
);

it('returns a MaximoResponse object', function() {
    Http::fake([
        '*' => Http::response(require __DIR__ . '/../stubs/responses/single-record.php')
    ]);

    $response = MaximoQuery::withObjectStructure('trim')
        ->where('sausage','eggs')
        ->update([]);

    $this->assertInstanceOf(MaximoResponse::class, $response);
});





