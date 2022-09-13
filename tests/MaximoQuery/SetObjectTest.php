<?php

use NetworkRailBusinessSystems\MaximoQuery\Exceptions\InvalidQuery;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

it('throws an exception if object type is not set', function() {
    $this->expectException(InvalidQuery::class);
    $this->expectExceptionMessage("Object type not set! Use the 'withObjectStructure()' or the 'withMaximoBusinessObject()' methods and pass in the relevant data.");

    MaximoQuery::getUrl();
});


test('withObjectStructure method returns the correct query string', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringContainsString('os/mxperson', $url);
});


test('withBusinessObject method returns the correct query string', function() {
    $url = MaximoQuery::withBusinessObject('person')
        ->getUrl();

    $this->assertStringContainsString('mbo/person', $url);
});
