<?php

namespace Nrbusinesssystems\MaximoQuery;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;
use Nrbusinesssystems\MaximoQuery\Traits\HasWhere;

class MaximoQuery
{
    use HasWhere;

    private ?array $columns = null;

    private ?string $objectType = null;

    private ?string $queryObject = null;

    private ?int $uniqueId = null;

    private ?int $pageSize = 1000;

    private bool $dropNulls = false;

    private bool $collectionCount = false;

    private bool $count = false;

    private array $orderBy = [];

    private ?int $page = null;


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
        $this->count = true;

        return $this->get()
            ->getCount();
    }

    /**
     * Will request that null values are filtered from the response
     *
     * @param bool $filterNullValues
     * @return $this
     */
    public function filterNullValues(bool $filterNullValues = true): self
    {
        $this->dropNulls = $filterNullValues;

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
     * @param string|array $columns
     * @return $this
     */
    public function select($columns): self
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
     * @param string|array $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, string $direction = 'desc'): self
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
     * @param $uniqueId
     * @return array
     * @throws Exceptions\CouldNotAuthenticate
     * @throws Exceptions\InvalidResponse
     * @throws InvalidQuery
     */
    public function find($uniqueId): array
    {
        $this->uniqueId = $uniqueId;

        return $this->get()
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

        return (new MaximoHttp($this->getUrl()))
            ->get();
    }

    /**
     * @throws Exceptions\CouldNotAuthenticate
     * @throws InvalidQuery
     * @throws Exceptions\InvalidResponse
     */
    public function create(array $properties, $returnResource = false): MaximoResponse
    {
        return (new MaximoHttp($this->getUrl()))
            ->post( $properties, $returnResource);
    }

    public function update()
    {
        //needs resource url
    }

    public function delete()
    {
        //needs resource url
    }


    /**
     * Gets the url for the http request
     *
     * @param bool $withQueryParameters
     * @return string
     * @throws InvalidQuery
     */
    public function getUrl(bool $withQueryParameters = true): string
    {
        if ($withQueryParameters === false) {
            return $this->getBaseUrl();
        }

        return $this->buildUrl();
    }


    /**
     * Returns an array of query parameters for the url
     * i.e. ?oslc.select=firstname,lastname....
     *
     * @return array
     */
    private function getQueryParameters(): array
    {
        if ($this->uniqueId) {
            return [
                $this->getSelect()
            ];
        }

        if ($this->count) {
            return [
                $this->getWhere(),
                "count=1"
            ];
        }

        return [
            $this->getSelect(),
            $this->getWhere(),
            $this->getOrderBy(),
            $this->getPage(),
            $this->getPageSize(),
            $this->getDropNulls(),
            $this->getCollectionCount()
        ];
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
     * Builds the url from the various parameters set
     * during the construction of the query object
     *
     * @return string
     * @throws InvalidQuery
     */
    private function buildUrl(): string
    {
        $params = collect($this->getQueryParameters())
            ->filter()
            ->implode('&');

        return "{$this->getBaseUrl()}{$this->getUniqueId()}?{$params}";
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
     * Generates the uniqueId portion of the url
     * if the find() method has been called
     *
     * @return string|void
     */
    private function getUniqueId()
    {
        if (blank($this->uniqueId)) {
            return;
        }

        return "/{$this->uniqueId}";
    }


    /**
     * Generates the orderBy portion of the url
     * if the orderBy method has been called
     *
     * @return string|void
     */
    private function getOrderBy()
    {
        if (blank($this->orderBy)) {
            return;
        }

        $imploded = collect($this->orderBy)
            ->implode(',');

        return  "oslc.orderBy={$imploded}";
    }


    /**
     * Generates the collectioncount portion of the query string
     * if withCount() has been called
     *
     * @return string|void
     */
    private function getCollectionCount()
    {
        if (!$this->collectionCount) {
            return;
        }

        return "collectioncount=1";
    }


    /**
     * Generates the dropnulls portion of the query string
     *
     * @return string|void
     */
    private function getDropNulls()
    {
        $dropNulls = (int) $this->dropNulls;

        return "_dropnulls={$dropNulls}";
    }


    /**
     * Generates the pageno portion of the query string
     *
     * @return string|void
     */
    private function getPage()
    {
        if (!$this->page) {
            return;
        }

        return "pageno={$this->page}";
    }

}
