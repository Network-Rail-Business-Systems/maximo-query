<?php

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

it('selects no columns by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringNotContainsString('oslc.select', $url);
});


test('selectAll method returns the correct query string partial', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->selectAll()
        ->getUrl();

    $this->assertStringContainsString('oslc.select=*', $url);
});


test('specific columns can be requested', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->select(['column1', 'column2', 'column3'])
        ->getUrl();

    $this->assertStringContainsString('oslc.select=column1,column2,column3', $url);
});
