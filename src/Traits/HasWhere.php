<?php

namespace Nrbusinesssystems\MaximoQuery\Traits;


use Illuminate\Support\Arr;
use Nrbusinesssystems\MaximoQuery\Exceptions\InvalidQuery;

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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function whereStartsWith($column, $search): self
    {
        $this->where[] = "{$column}=\"{$search}%\"";

        return $this;
    }


    /**
     * @param $column
     * @param $search
     * @return $this
     */
    public function whereEndsWith($column, $search): self
    {
        $this->where[] = "{$column}=\"%{$search}\"";

        return $this;
    }


    /**
     * @param $column
     * @param $search
     * @return $this
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
     * @return $this
     */
    public function whereNotNull($column)
    {
        $this->where[] = "{$column}=\"*\"";

        return $this;
    }


    /**
     * @return string|void
     */
    private function getWhere()
    {
        if (blank($this->where)) {
            return;
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
     * @return string
     */
    private function quoteString($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        return '"' . $value . '"';
    }

}
