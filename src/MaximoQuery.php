<?php

namespace Nrbusinesssystems\MaximoQuery;

use Illuminate\Support\Arr;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidResponse;
use Nrbusinesssystems\MaximoQuery\Traits\HasWhere;

class MaximoQuery
{
    use HasWhere;

    private ?array $columns = null;

    private ?string $objectType = null;

    private ?string $queryObject = null;

    private ?int $pageSize = 1000;

    private bool $dropNulls = false;

    private bool $collectionCount = false;

    private bool $count = false;

    private array $orderBy = [];

    private ?int $page = null;

    private ?string $url = null;

    private ?bool $debug = false;

    /**
     * Allows you to view all the class parameters
     * @return $this
     */
    public function debug(): static
    {
        $this->debug = true;

        return $this;
    }


    /**
     * Set the pagination for the results
     * Default page size is set to 1000
     *
     * @param int $pageSize
     * @return $this
     */
    public function paginate(int $pageSize = 20): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }


    /**
     * Remove all pagination
     * WARNING: This should be used with extreme caution!
     *
     * @return $this
     */
    public function withoutPagination(): self
    {
        $this->pageSize = null;

        return $this;
    }


    /**
     * Includes the query count with the response data
     *
     * @return $this
     */
    public function withCount(): self
    {
        $this->collectionCount = true;

        return $this;
    }


    /**
     * Returns the query count
     *
     * @return int|null
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     * @throws InvalidQuery
     */
    public function count(): ?int
    {
//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//            $this->getWhere(),
//            "count=1"
//        );
//
//        $this->url = "{$this->getBaseUrl()}?{$queryString}";

        $this->count = true;

        return (new MaximoHttp(url: $this->getUrl(), debug: $this->debug))
            ->get()
            ->getCount();
    }

    /**
     * Will request that null values are filtered from the response
     *
     * @return $this
     */
    public function filterNullValues(): self
    {
        $this->dropNulls = true;

        return $this;
    }


    /**
     * Sets the objectType to 'os'
     * and the queryObject to $objectStructure
     *
     * @param string $objectStructure
     * @return $this
     */
    public function withObjectStructure(string $objectStructure): self
    {
        $this->objectType = 'os';
        $this->queryObject = $objectStructure;

        return $this;
    }


    /**
     * Sets the objectType to 'mbo'
     * and the queryObject to $businessObject
     *
     * @param string $businessObject
     * @return $this
     */
    public function withBusinessObject(string $businessObject): self
    {
        $this->objectType = 'mbo';
        $this->queryObject = $businessObject;

        return $this;
    }


    /**
     * @param array|string $columns
     * @return $this
     */
    public function select(array|string $columns): self
    {
        $this->columns = Arr::wrap($columns);

        return $this;
    }


    /**
     * @return $this
     */
    public function selectAll(): self
    {
        $this->columns = ["*"];

        return $this;
    }


    /**
     * @param array|string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(array|string $column, string $direction = 'desc'): self
    {
        if (is_array($column)) {
            $this->orderBy = array_map(function($array) {
                [$columnName, $direction] = $array;

                $direction = $direction === 'desc' ? '-' : '+';

                return "{$direction}{$columnName}";
            }, $column);
        } else {
            $direction = $direction === 'desc' ? '-' : '+';

            $this->orderBy = ["{$direction}{$column}"];
        }

        return $this;
    }


    /**
     * Retrieves a single record using it's
     * unique identifier
     *
     * @param string $restId
     * @return array|null
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     * @throws InvalidQuery
     */
    public function find(string $restId): null|array
    {
//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//            $this->getSelect(),
//        );
//
//        $this->url = "{$this->getBaseUrl()}/{$restId}?{$queryString}";

        return (new MaximoHttp(url: $this->getUrl($restId), debug: $this->debug))
            ->get()
            ->toArray();
    }


    /**
     * Calls a new instance of MaximoHttp
     * which returns a new MaximoResponse
     *
     * @param int|null $page
     * @return MaximoResponse
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     * @throws InvalidQuery
     */
    public function get(int $page = null): MaximoResponse
    {
        $this->page = $page;

//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//            $this->getSelect(),
//            $this->getWhere(),
//            $this->getOrderBy(),
//            $this->getPage(),
//            $this->getPageSize(),
//            $this->getDropNulls(),
//            $this->getCollectionCount(),
//        );
//
//        $this->url = "{$this->getBaseUrl()}?{$queryString}";

        return (new MaximoHttp(url: $this->getUrl(), debug: $this->debug))
            ->get();
    }

    /**
     * @throws Exceptions\CouldNotAuthenticate
     * @throws InvalidQuery
     * @throws Exceptions\InvalidResponse
     */
    public function create(array $data, array $properties = []): MaximoResponse
    {
//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//        );
//
//        $this->url = "{$this->getBaseUrl()}?{$queryString}";

        $data = array_lowercase_keys(array: $data);
        $properties = array_lowercase_values(array: $properties);

        return (new MaximoHttp(url: $this->getUrl(), debug: $this->debug))
            ->post(data: $data, returnedProperties: $properties);
    }

    /**
     * @throws Exceptions\CouldNotAuthenticate
     * @throws InvalidQuery
     * @throws Exceptions\InvalidResponse
     * @throws Exceptions\KeyNotFound
     */
    public function update(array $data, array $properties = []): MaximoResponse
    {
        $url = $this->getResourceUrl();

        $data = array_lowercase_keys(array: $data);
        $properties = array_lowercase_values(array: $properties);

        return (new MaximoHttp(url: $url))
            ->patch(data: $data, returnedProperties: $properties);
    }

    /**
     * @throws InvalidQuery
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\KeyNotFound
     * @throws Exceptions\InvalidResponse
     */
    private function getResourceUrl(): string
    {
        $where = $this->getWhere();

        if (is_null($where)) {
            throw InvalidQuery::noWhereClause();
        }

        //force the minimum pagination to check for a single resource
        $this->paginate(2);

//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//            $where
//        );
//
//        $this->url = "{$this->getBaseUrl()}?{$queryString}";

        $resource = (new MaximoHttp(url: $this->getUrl(), debug: $this->debug))
            ->get()
            ->filter('member')
            ->pluck('href');

        if ($resource->isEmpty()) {
            throw InvalidResponse::resourceNotFound();
        }

        if ($resource->count() > 1) {
            throw InvalidResponse::multipleResourcesFound();
        }

//        $queryString = $this->getQueryString(
//            $this->getDefaultParameters(),
//        );
//
//        return "{$this->getBaseUrl()}/{$resource->first()}?{$queryString}";

        return $this->getUrl($resource->first());
    }

    /**
     * @throws InvalidQuery
     */
    private function getBaseUrl(): string
    {
        if (!$this->objectType) {
            throw InvalidQuery::objectTypeNotSet();
        }

        $url = config('maximo-query.maximo_url');

        return "{$url}/oslc/{$this->objectType}/{$this->queryObject}";
    }

    /**
     * @throws InvalidQuery
     */
    public function getUrl(...$pathParameters): string
    {
        $queryString = $this->getQueryString();
        $baseUrl = $this->getBaseUrl();
        $path = collect($pathParameters)
            ->whenNotEmpty(
                fn ($collection) => '/' . $collection->implode('/'),
                fn () => ''
            );

        return "{$baseUrl}{$path}?{$queryString}";
    }


    /**
     * Generates the select portion of the query string
     * if the columns have been set
     *
     * @return string|void
     */
    private function getSelect()
    {
        if (blank($this->columns)) {
            return;
        }

        $imploded = collect($this->columns)
            ->implode(',');

        return  "oslc.select={$imploded}";
    }


    /**
     * Generates the pageSize portion of the query string
     * if pagination has been set
     *
     * @return string|void
     */
    private function getPageSize()
    {
        if (blank($this->pageSize)) {
            return;
        }

        return "oslc.pageSize={$this->pageSize}";
    }

    /**
     * Generates the count portion of the query string
     * if count method has been called
     *
     * @return string|void
     */
    private function getCount()
    {
        if ($this->count === false) {
            return;
        }

        return 'count=1';
    }


    /**
     * Generates the orderBy portion of the url
     */
    private function getOrderBy(): string|null
    {
        if (blank($this->orderBy)) {
            return null;
        }

        $imploded = collect($this->orderBy)
            ->implode(',');

        return  "oslc.orderBy={$imploded}";
    }


    /**
     * Generates the collectioncount portion of the query string
     */
    private function getCollectionCount(): string|null
    {
        if ($this->collectionCount === false) {
            return null;
        }

        return "collectioncount=1";
    }


    /**
     * Generates the dropnulls portion of the query string
     */
    private function getDropNulls(): string|null
    {
        //The default implementation of the api will filter all properties with null values
        if ($this->dropNulls === true) {
            return null;
        }

        return '_dropnulls=0';
    }


    /**
     * Generates the pageno portion of the query string
     */
    private function getPage(): string|null
    {
        if (is_null($this->page)) {
            return null;
        }

        return "pageno={$this->page}";
    }

    private function getDefaultParameters(): string
    {
        return 'lean=1&relativeuri=1';
    }

    private function getQueryString(): string
    {
        $parameters = [
            $this->getDefaultParameters(),
            $this->getSelect(),
            $this->getWhere(),
            $this->getOrderBy(),
            $this->getPage(),
            $this->getPageSize(),
            $this->getDropNulls(),
            $this->getCollectionCount(),
            $this->getCount(),
        ];

        return collect($parameters)
            ->flatten()
            ->filter()
            ->implode('&');
    }
}
