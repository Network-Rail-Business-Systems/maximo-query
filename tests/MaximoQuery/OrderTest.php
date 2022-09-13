<?php

use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

it('can order by a single column', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->orderBy('column1', 'desc')
        ->getUrl();

    $this->assertStringContainsString('oslc.orderBy=-column1', $url);
});



it('can order by a multiple columns', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->orderBy([
            ['column1', 'desc'],
            ['column2', 'asc']
        ])
        ->getUrl();

    $this->assertStringContainsString('oslc.orderBy=-column1,+column2', $url);
});
