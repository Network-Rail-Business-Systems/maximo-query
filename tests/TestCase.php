<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;
use NetworkRailBusinessSystems\MaximoQuery\Providers\MaximoQueryServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [MaximoQueryServiceProvider::class];
    }

    public function clearCookies()
    {
        Cache::forget(config('maximo-query.cookie_cache_key'));
    }

    public function fakeLogin()
    {
        Cache::put(
            config('maximo-query.cookie_cache_key'),
            new CookieJar(),
            now()->addMinutes(config('maximo-query.cache_ttl_minutes', 60))
        );
    }

    protected function getTestFilePath(string $file): string
    {
        return __DIR__.'/Data/'.$file;
    }
}
