<?php

namespace NetworkRailBusinessSystems\MaximoQuery\Tests\Unit\MaximoHttp;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\MaximoQuery\Facades\MaximoQuery;
use NetworkRailBusinessSystems\MaximoQuery\Tests\TestCase;

class CookieCacheTest extends TestCase
{
    public function testCookiesStoredInCacheForDurationOfLifetime()
    {
        Config::set('maximo-query.cache_ttl_minutes', 60);
    
        $cacheKey = config('maximo-query.cookie_cache_key');
    
        $this->clearCookies();
    
        Http::fake();
    
        MaximoQuery::withObjectStructure('mxperson')
            ->get();
    
        $this->assertTrue(Cache::has($cacheKey));
    
        Carbon::setTestNow(Carbon::now()->addMinutes(61));
    
        $this->assertFalse(Cache::has($cacheKey));
    }
}
