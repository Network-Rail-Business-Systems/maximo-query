<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use NetworkRailBusinessSystems\MaximoQuery\Exceptions\CouldNotAuthenticate;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testAuthenticatesIfCookiesAreNotCached(): void
    {
        $this->fakeHttp(false);
        $this->clearCookies();
        
        MaximoQuery::withObjectStructure('mxperson')->get();
    
        Http::assertSent(function ($request) {
            return Str::contains($request->url(), 'j_security_check');
        });
    
        $this->assertInstanceOf(CookieJar::class, Cache::get(config('maximo-query.cookie_cache_key')));
    }
    
    public function testThrowsExceptionIfUsernameOrPasswordAreNotSetInConfig(): void
    {
        $this->fakeHttp(true);
        $this->expectException(CouldNotAuthenticate::class);
        $this->expectExceptionMessage("The 'username' and/or 'password' has not be set in the config file!");
    
        Config::set('maximo-query.maximo_username', null);
    
        $this->clearCookies();
    
        MaximoQuery::withObjectStructure('mxperson')->get();
    }
    
    public function testThrowsExceptionIfCannotAuthenticate(): void
    {
        $this->fakeHttp(true);
        $this->expectException(CouldNotAuthenticate::class);
        
        $this->clearCookies();
    
        MaximoQuery::withObjectStructure('mxperson')->get();
    }
    
    public function testDoesNotAuthenticateIfCookiesAreCached(): void
    {
        $this->fakeHttp(false);
        $this->fakeLogin();
        
        MaximoQuery::withObjectStructure('mxperson')->get();
    
        Http::assertNotSent(function ($request) {
            return Str::contains($request->url(), 'j_security_check');
        });
    }
    
    protected function fakeHttp(bool $fails): void
    {
        $fails === true
            ? Http::fake(['*/j_security_check' => Http::response(null, 401)])
            : Http::fake(['*' => Http::response([])]);
    }
}
