<?php

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

test('count method returns an integer', function() {
    $this->fakeLogin();

    Http::fake([
        '*count=1*' => Http::response(["totalCount" => 2345]),
    ]);

    $count = MaximoQuery::withObjectStructure('mxperson')
        ->count();

    $this->assertIsInt($count);
    $this->assertSame(2345, $count);
});
