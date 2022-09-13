<?php

use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('does not drop null values by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringContainsString('_dropnulls=0', $url);
});

it('url does not contain dropnulls when filterNullValues method is chained', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->filterNullValues()
        ->getUrl();

    $this->assertStringNotContainsString('_dropnulls=0', $url);
});
