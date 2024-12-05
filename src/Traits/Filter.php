<?php

namespace JanakKapadia\Filterable\Traits;

trait Filter
{
    public function scopeFilter($query, bool $isWhereBindParentheses = false)
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $class = 'App\\Filters\\'.$className.'Filters';
        return resolve($class)->setWhereBindParentheses($isWhereBindParentheses)->apply($query);
    }
}
