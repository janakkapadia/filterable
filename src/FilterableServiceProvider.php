<?php

namespace JanakKapadia\Filterable;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use JanakKapadia\Filterable\Commands\MakeFilterCommand;

class FilterableServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeFilterCommand::class,
            ]);
        }
    }
}
