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
     * Custom filter key value pairs
     *
     * @var array
     */
    protected array $values = [];

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
                $this->$filter($value);
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
        if (count($this->values)) {
            return array_filter(Arr::only($this->values, $this->filters));
        }

        return array_filter($this->request->only($this->filters));
    }
}
