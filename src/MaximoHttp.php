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
     * @return MaximoResponse
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    public function get(): MaximoResponse
    {
        if (!Cache::has($this->cacheKey)) {
            $this->authenticate();
        }

        $response = $this->validateResponse(
            Http::withOptions([
                'cookies' => Cache::get($this->cacheKey)
            ])->get($this->url)
        );

        return new MaximoResponse($response, $this->url);
    }

    /**
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    public function post(array $properties, $returnResource = false): MaximoResponse
    {
        if (!Cache::has($this->cacheKey)) {
            $this->authenticate();
        }

        $options = ['cookies' => Cache::get($this->cacheKey)];

        if ($returnResource === true) {
            $options = array_merge(
                $options,
                [
                    'headers' => [
                        'properties' => '*'
                    ]
                ]
            );
        }

        $response = $this->validateResponse(
            Http::withOptions($options)
                ->post($this->url, $properties)
        );

        return new MaximoResponse($response, $this->url);
    }

    public function patch()
    {

    }

    public function delete()
    {

    }

    /**
     * @throws InvalidResponse
     */
    protected function validateResponse($response)
    {
        if ($response->ok()) {
            return $response;
        }

        throw InvalidResponse::notOk($response->toPsrResponse());
    }

}
