<?php

use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('will not request collection count by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringNotContainsString('_collectioncount', $url);
});


test('collection count can be enabled', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->withCount()
        ->getUrl();

    $this->assertStringContainsString('collectioncount=1', $url);
});
