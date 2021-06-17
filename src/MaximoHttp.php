<?php

namespace Nrbusinesssystems\MaximoQuery;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\CouldNotAuthenticate;
use Nrbusinesssystems\MaximoQuery\Exceptions\Debug;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;

class MaximoHttp
{
    private string $cacheKey;

    private ?string $username;

    private ?string $password;

    private int $cacheLifetime;

    private array $headers = [];

    private array $options = [];

    private Request|null $request;


    /**
     * MaximoHttp constructor.
     * @param string $url
     * @param bool $debug
     */
    public function __construct(
        private string $url,
        private bool $debug = false
    ){
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
    private function authenticate(): void
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
        $this->getClient()->dump();

        $this->setCookies();

        $response = $this->validateResponse(
            $this->getClient()
                ->get(url: $this->url)
        );

        return new MaximoResponse($response, $this->url);
    }

    /**
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    public function post(array $data, array $returnedProperties = []): MaximoResponse
    {
        $this->setCookies();

        $this->setProperties(properties: $returnedProperties);

        $response = $this->validateResponse(
            $this->getClient()
                ->post(
                    url: $this->url,
                    data: $data
                )
        );

        return new MaximoResponse($response, $this->url);
    }

    /**
     * @throws CouldNotAuthenticate
     * @throws InvalidResponse
     */
    public function patch(array $data, array $returnedProperties = []): MaximoResponse
    {
        $this->setCookies();

        $this->setProperties(properties: $returnedProperties);

        $this->addHeader(
            key: 'x-method-override',
            value: 'PATCH'
        );

        $response = $this->validateResponse(
            $this->getClient()
                ->post(
                    url: $this->url,
                    data: $data
                )
        );

        return new MaximoResponse($response, $this->url);
    }

    /**
     * @throws InvalidResponse
     */
    private function validateResponse(Response $response): Response
    {
        if ($response->successful()) {
            return $response;
        }

        throw InvalidResponse::notSuccessful($response);
    }

    /**
     * @throws CouldNotAuthenticate
     */
    private function setCookies(): void
    {
        if (!Cache::has($this->cacheKey)) {
            $this->authenticate();
        }

        $this->options = ['cookies' => Cache::get($this->cacheKey)];
    }

    private function addHeader($key, $value): void
    {
        $this->headers[$key] = $value;
    }

    private function setProperties(array $properties = []): void
    {
        $properties = empty($properties) === false
            ? collect($properties)->implode(',')
            : '_rowstamp,href';

        $this->addHeader(
            key: 'properties',
            value: $properties
        );
    }

    private function getClient(): PendingRequest
    {
        return Http::withHeaders($this->headers)
            ->withOptions($this->options);
//            ->beforeSending(function ($request) {
//                if ($this->debug === true) {
//                    throw Debug::dumpRequest($request);
//                }
//            });
    }

}
