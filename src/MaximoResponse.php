<?php

namespace Nrbusinesssystems\MaximoQuery;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Nrbusinesssystems\MaximoQuery\Exceptions\KeyNotFound;

class MaximoResponse
{
    public function __construct(
        private Response $response,
        private string $queryUrl
    ){}


    /**
     * Returns the value of a given key in the response
     *
     * @throws KeyNotFound
     */
    public function filter(string $searchKey, bool $toCollection = true): mixed
    {
        $results = $this->arraySearchRecursive($this->toArray(), $searchKey);

        return $toCollection ? collect($results) : $results;
    }


    /** Gets the total count from the response array */
    public function getCount(): int
    {
        try {
            return $this->filter(searchKey: 'totalCount', toCollection: false);
        } catch (KeyNotFound $e) {
            return 0;
        }
    }


    /** Returns the raw response */
    public function raw(): Response
    {
        return $this->response;
    }


    /** Returns the response data as an associative array */
    public function toArray(): array|null
    {
        return json_decode($this->response->body(), true);
    }


    /** Returns the response data as a collection */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }


    /**
     * Gets the next page of the paginated dataset
     *
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    public function nextPage(): MaximoResponse|null
    {
        return $this->getPage('nextPage');
    }


    /**
     * Gets the previous page of the paginated dataset
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    public function prevPage(): MaximoResponse|null
    {
        return $this->getPage('previousPage');
    }


    /** Returns the query URL */
    public function getUrl(): string
    {
        return $this->queryUrl;
    }


    /**
     * Recursively searches an array for a given key
     * and returns it's value if found
     *
     * @throws KeyNotFound
     */
    private function arraySearchRecursive(array $array, string $search): mixed
    {
        $iterator = new \RecursiveArrayIterator($array);

        foreach(new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST) as $key => $value) {
            if ($key === $search) {
                return $value;
            }
        }

        throw KeyNotFound::inResponse($search);
    }


    /**
     * Gets the page specified by the $page parameter
     *
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    private function getPage(string $page): MaximoResponse|null
    {
        try {
            $pageResource = $this->filter($page, false);
        } catch (KeyNotFound $e) {
            return null;
        }

        $url = config('maximo-query.maximo_url') . '/' . $pageResource['href'];



        return (new MaximoHttp($url))
            ->get();
    }

    public function __toString(): string
    {
        return $this->response->json();
    }

}
