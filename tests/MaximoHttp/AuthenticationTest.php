<?php

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\CouldNotAuthenticate;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;

it('authenticates if cookies are not cached', function() {
    $this->clearCookies();

    Http::fake();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();

    Http::assertSent(function ($request) {
        return Str::contains($request->url(), 'j_security_check');
    });

    $this->assertInstanceOf(CookieJar::class, Cache::get(config('maximo-query.cookie_cache_key')));
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
    Http::fake([
        '*/j_security_check' => Http::response(null, 401),
    ]);

    $this->clearCookies();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();
})->throws(CouldNotAuthenticate::class);



it('does not authenticate if cookies are cached', function() {
    $this->fakeLogin();

    Http::fake();

    MaximoQuery::withObjectStructure('mxperson')
        ->get();

    Http::assertNotSent(function ($request) {
        return Str::contains($request->url(), 'j_security_check');
    });
});
