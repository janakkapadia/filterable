<?php

namespace JanakKapadia\Filterable\Traits;

trait Filter
{
    public function scopeFilter($query)
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $class = 'App\\Filters\\'.$className.'Filters';
        return resolve($class)->apply($query);
    }
}
