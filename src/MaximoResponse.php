<?php

namespace Nrbusinesssystems\MaximoQuery;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nrbusinesssystems\MaximoQuery\Exceptions\KeyNotFound;

class MaximoResponse
{
    private string $rawResponse;


    public function __construct(string $response)
    {
        $this->rawResponse = $response;
    }


    /**
     * Returns the value of a given key in the response
     *
     * @param $searchKey
     * @param bool $toCollection
     * @return Collection|mixed
     * @throws KeyNotFound
     */
    public function filter($searchKey, $toCollection = true)
    {
        $results = $this->arraySearchRecursive($this->toArray(), $searchKey);

        if ($toCollection) {
            return collect($results);
        }

        return $results;
    }

    /**
     * Gets the total count from the response array
     *
     * @return int|null
     * @throws KeyNotFound
     */
    public function getCount(): ?int
    {
        return $this->filter('totalCount', false);
    }


    /**
     * Returns the raw json response
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->rawResponse;
    }


    /**
     * Returns the response data as an associative array
     *
     * @return array
     */
    public function toArray():? array
    {
        return json_decode($this->rawResponse, true);
    }


    /**
     * Returns the response data as a collection
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }


    /**
     * Gets the next page of the paginated dataset
     *
     * @return MaximoResponse|null
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    public function nextPage()
    {
        return $this->getPage('nextPage');
    }


    /**
     * Gets the previous page of the paginated dataset
     *
     * @return MaximoResponse|null
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    public function prevPage()
    {
        return $this->getPage('previousPage');
    }


    /**
     * Recursively searches an array for a given key
     * and returns it's value if found
     *
     * @param $array
     * @param $search
     * @return mixed
     * @throws KeyNotFound
     */
    private function arraySearchRecursive($array, $search)
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
     * @param string $page
     * @return MaximoResponse|null
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     */
    private function getPage(string $page)
    {
        try {
            $pageResource = $this->filter("oslc:{$page}", false);
        } catch (KeyNotFound $e) {
            return null;
        }

        $url = str_replace('http://localhost/maximo', config('maximo-query.maximo_url'), $pageResource['rdf:resource']);

        return (new MaximoHttp($url))
            ->get();
    }

}
