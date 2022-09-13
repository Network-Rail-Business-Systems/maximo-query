<?php

use Illuminate\Support\Facades\Http;
use Networkrailbusinesssystems\MaximoQuery\Facades\MaximoQuery;

it('returns the url', function() {
    Http::fake();

    $instance = MaximoQuery::withObjectStructure('mxperson');
    $url = $instance->getUrl();

    $response = $instance->get();

    $this->assertSame($url, $response->getUrl());
});
