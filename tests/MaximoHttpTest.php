<?php

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Nrbusinesssystems\MaximoQuery\Exceptions\CouldNotAuthenticate;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Nrbusinesssystems\MaximoQuery\Facades\MaximoQuery;
use Nrbusinesssystems\MaximoQuery\MaximoResponse;


beforeEach(function () {
    $this->httpCacheKey = config('maximo-query.cookie_cache_key');

    $this->fakeLogin();
});






it('throws an exception if response status is not a 200 code', function () {
    $this->expectException(InvalidResponse::class);

    Http::fake([
        '*/oslc/*' => Http::response(require __DIR__ . '/stubs/responses/error-404.php', 404),
    ]);

    MaximoQuery::withObjectStructure('mxperson')
        ->get();
});



it('returns an instance of the maximo response class', function () {
    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->get();

    $this->assertInstanceOf(MaximoResponse::class, $response);
});




