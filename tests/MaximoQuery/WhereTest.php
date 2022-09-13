<?php


namespace Networkrailbusinesssystems\MaximoQuery\Tests;

use Networkrailbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

test('passing an invalid operator to where throws an exception', function(string $operator, bool $expectException) {
    if ($expectException) {
        $this->expectException(InvalidQuery::class);
        $this->expectExceptionMessage("Invalid operator passed to 'where()' method. Please use one of the following: \n" . print_r(['=', '>=', '>', '<', '⇐', '!='], true));
    }

    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', $operator, 'some value')
        ->getUrl();

    $this->assertStringContainsString("oslc.where=column1{$operator}\"some value\"", $url);
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

    $this->assertStringContainsString('oslc.where=column1="some value"', $url);
});



test('numeric values passed to where method are not quoted', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', 100)
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1=100', $url);
});



it('can add where in clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereIn('column1', ['tom', 'dick', 'harry'])
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1 in ["tom","dick","harry"]', $url);
});



it('can add where not in clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNotIn('column1', ['tom', 'dick', 'harry'])
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1!="[tom,dick,harry]"', $url);
});


it('can add where starts with clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereStartsWith('column1', 'some value')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1="some value%"', $url);
});



it('can add where ends with clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereEndsWith('column1', 'some value')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1="%some value"', $url);
});



it('can add where like clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereLike('column1', 'some value')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1="%some value%"', $url);
});



it('can add where null clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNull('column1')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1!="*"', $url);
});



it('can add where not null clause to query', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->whereNotNull('column1')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1="*"', $url);
});

test('where methods can be chained', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->where('column1', 'some value')
        ->where('column2', 'another value')
        ->getUrl();

    $this->assertStringContainsString('oslc.where=column1="some value" and column2="another value"', $url);
});
