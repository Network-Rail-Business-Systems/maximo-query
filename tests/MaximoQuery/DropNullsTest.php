<?php

use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('does not drop null values by default', function() {
    $url = MaximoQuery::withObjectStructure('mxperson')
        ->getUrl();

    $this->assertStringContainsString('_dropnulls=0', $url);
});
