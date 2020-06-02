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


it('authenticates if cookies are not cached', function() {
    $this->clearCookies();

    Http::fake();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();

    Http::assertSent(function ($request) {
        return Str::contains($request->url(), 'j_security_check');
    });

    assertInstanceOf(CookieJar::class, Cache::get($this->httpCacheKey));
});




it('throws an exception if username or password are not set in the config', function(){
    $this->expectException(CouldNotAuthenticate::class);
    $this->expectExceptionMessage("The 'username' and/or 'password' has not be set in the config file!");

    Config::set('maximo-query.maximo_username', null);

    $this->clearCookies();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();
});



it('throws an exception if it cannot authenticate', function () {
    $this->expectException(CouldNotAuthenticate::class);

    Http::fake([
        '*/j_security_check' => Http::response(null, 401),
    ]);

    $this->clearCookies();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();
});



it('does not authenticate if cookies are cached', function() {
    Http::fake();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();

    Http::assertNotSent(function ($request) {
        return Str::contains($request->url(), 'j_security_check');
    });
});



it('throws an exception if response status is not a 200 code', function () {
    $this->expectException(InvalidResponse::class);

    Http::fake([
        '*/oslc/*' => Http::response(null, 400),
    ]);

    MaximoQuery::withObjectStructure('mxperson')
        ->get();
});



it('returns an instance of the maximo response class', function () {
    Http::fake();

    $response = MaximoQuery::withObjectStructure('mxperson')
        ->get();

    assertInstanceOf(MaximoResponse::class, $response);
});



test('cookies are only stored in the cache for the duration of the cache lifetime', function () {
    Config::set('maximo-query.cache_ttl_minutes', 60);

    $this->clearCookies();

    Http::fake();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();

    assertTrue(Cache::has($this->httpCacheKey));

    Carbon::setTestNow(Carbon::now()->addMinutes(61));

    assertFalse(Cache::has($this->httpCacheKey));
});
