<?php

namespace JanakKapadia\Filterable\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class Filter implements FilterContract
{
    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected Builder $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected array $filters = [];

    /**
     * Registered order by columns.
     *
     * @var array
     */
    protected array $sort = [];

    /**
     * Custom filter key value pairs
     *
     * @var array
     */
    protected array $values = [];

    /**
     * Whether to wrap where conditions in parentheses
     *
     * @var bool
     */
    protected bool $whereBindParentheses = false;

    /**
     * Create a new ThreadFilters instance.
     * @param Request $request
     */
    public function __construct(public Request $request){}

    /**
     * Set custom filter key value pairs
     */
    public function values(array $values = []): static
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Set whether to wrap where conditions in parentheses
     */
    public function setWhereBindParentheses(bool $whereBindParentheses): static
    {
        $this->whereBindParentheses = $whereBindParentheses;

        return $this;
    }

    /**
     * Apply the filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                if (in_array($filter, $this->sort)) {
                    $this->$filter($value);
                } else if ($this->whereBindParentheses) {
                    $this->builder->where(function($query) use ($filter, $value) {
                        $originalBuilder = $this->builder;
                        $this->builder = $query;
                        $this->$filter($value);
                        $this->builder = $originalBuilder;
                    });
                } else {
                    $this->$filter($value);
                }
            }
        }

        return $this->builder;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    private function getFilters(): array
    {
        $allowedFilters = array_merge($this->filters, $this->sort);
        
        if (count($this->values)) {
            return array_filter(Arr::only($this->values, $allowedFilters));
        }

        return array_filter($this->request->only($allowedFilters));
    }
}
