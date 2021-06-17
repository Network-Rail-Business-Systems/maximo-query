<?php

use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('will throw an exception if there is no where clause', function() {
    MaximoQuery::withObjectStructure('trim')
        ->update([]);
})->throws(
    exceptionClass: InvalidQuery::class,
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
    exceptionClass: InvalidResponse::class,
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
    exceptionClass: InvalidResponse::class,
    exceptionMessage: 'A resource could not be found. Please try different parameters.'
);

//it('it updates the resource and returns the the correct properties', function() {
//    Http::fake([
//        '*' => Http::response(require __DIR__ . '/../stubs/responses/update-with-properties.php')
//    ]);
//
//    $responseData = MaximoQuery::withObjectStructure('trim')
//        ->where('ticketid','ABEY12351')
//        ->update(
//            ['description' => 'Maximo Query Test Update'],
//            ['ticketid', 'description', 'description_longdescription']
//        )
//        ->toArray();
//});
//
//it('it updates the resource and returns no properties');





