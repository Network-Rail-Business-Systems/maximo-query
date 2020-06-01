<?php

namespace Nrbusinesssystems\MaximoQuery;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\CouldNotAuthenticate;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;

class MaximoHttp
{

    private string $cacheKey;

    private string $url;

    private ?string $username;

    private ?string $password;

    private int $cacheLifetime;


    /**
     * MaximoHttp constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;

        $this->cacheKey = config('maximo-query.cookie_cache_key');

        $this->username = config('maximo-query.maximo_username');

        $this->password = config('maximo-query.maximo_password');

        $this->cacheLifetime = config('maximo-query.cache_ttl_minutes', 60);
    }


    /**
     * Logs into Maximo.
     * If successful, the response cookies are stored in the cache for subsequent requests
     *
     * @throws CouldNotAuthenticate
     */
    protected function authenticate()
    {
        if (!($this->username && $this->password)) {
            throw CouldNotAuthenticate::credentialsNotSetInConfig();
        }

        $response = Http::asForm()->post(config('maximo-query.maximo_url') . '/j_security_check', [
            'j_username' => $this->username,
            'j_password' => $this->password
        ]);

        if (!$response->ok()) {
            throw CouldNotAuthenticate::fromResponse($response);
        }

        Cache::put($this->cacheKey, $response->cookies(), now()->addMinutes($this->cacheLifetime));
    }


    /**
     * Makes a GET request to Maximo using the url
     * constructed by the query builder
     *
     * @return \Illuminate\Http\Client\Response
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    protected function getResponse()
    {
        if (!Cache::has($this->cacheKey)) {
            $this->authenticate();
        }

        $response = Http::withOptions([
            'cookies' => Cache::get($this->cacheKey)
        ])->get($this->url);

        if (!$response->ok()) {
            throw InvalidResponse::notOk($response->toPsrResponse());
        }

        return $response;
    }


    /**
     * @return MaximoResponse
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    public function get(): MaximoResponse
    {
        $response = $this->getResponse();

        return new MaximoResponse($response);
    }

}
