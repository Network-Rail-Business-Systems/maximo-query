<?php

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

it('has a default pagination', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringContainsString('oslc.pageSize=1000', $url);
});


test('pagination can be set to a specific value', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->paginate(100)
        ->getUrl();

    $this->assertStringContainsString('oslc.pageSize=100', $url);
});


test('pagination can be disabled', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->withoutPagination()
        ->getUrl();

    $this->assertStringNotContainsString('oslc.pageSize', $url);
});
