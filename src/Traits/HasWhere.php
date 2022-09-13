<?php

namespace Networkrailbusinesssystems\MaximoQuery\Traits;


use Illuminate\Support\Arr;
use Networkrailbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;

trait HasWhere
{

    private array $where = [];

    /**
     * Like eloquent's where, you can pass in just the column
     * and value if you are doing where equals, otherwise you can pass
     * in the operator as the 2nd parameter
     *
     * @param $column
     * @param $search
     * @param $value
     * @throws InvalidQuery
     */
    public function where($column, $operator = null, $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        } else {
            $validOperators = ['=', '>=', '>', '<', '⇐', '!='];

            if (!in_array($operator, $validOperators)) {
                throw InvalidQuery::invalidWhereOperator($validOperators);
            }
        }

        $this->where[] = "{$column}{$operator}{$this->quoteString($value)}";

        return $this;
    }


    /**
     * @param $column
     * @param $value
     */
    public function whereIn($column, $value): self
    {
        $imploded = collect(Arr::wrap($value))
            ->map(function ($value) {
                return $this->quoteString($value);
            })->implode(',');

        $this->where[] = "{$column} in [{$imploded}]";

        return $this;
    }


    /**
     * @param $column
     * @param $value
     */
    public function whereNotIn($column, $value): self
    {
        $imploded = collect(Arr::wrap($value))
            ->implode(',');

        $this->where[] = "{$column}!=\"[{$imploded}]\"";

        return $this;
    }


    /**
     * @param $column
     * @param $search
     */
    public function whereStartsWith($column, $search): self
    {
        $this->where[] = "{$column}=\"{$search}%\"";

        return $this;
    }


    /**
     * @param $column
     * @param $search
     */
    public function whereEndsWith($column, $search): self
    {
        $this->where[] = "{$column}=\"%{$search}\"";

        return $this;
    }


    /**
     * @param $column
     * @param $search
     */
    public function whereLike($column, $search): self
    {
        $this->where[] = "{$column}=\"%{$search}%\"";

        return $this;
    }


    /**
     * @param $column
     * @return $this
     */
    public function whereNull($column)
    {
        $this->where[] = "{$column}!=\"*\"";

        return $this;
    }


    /**
     * @param $column
     */
    public function whereNotNull($column): static
    {
        $this->where[] = "{$column}=\"*\"";

        return $this;
    }


    private function getWhere(): string|null
    {
        if (blank($this->where)) {
            return null;
        }

        $imploded = collect($this->where)
            ->implode(' and ');

        return "oslc.where={$imploded}";
    }


    /**
     * Adds quote to the passed in parameter if it is not numeric.
     * Used in the construction of the where clause.
     *
     * @param $value
     */
    private function quoteString($value): int|string
    {
        if (is_numeric($value)) {
            return $value;
        }

        return '"' . $value . '"';
    }

}
