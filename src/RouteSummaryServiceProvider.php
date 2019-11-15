<?php

namespace Biscofil\LaravelRouteSummary;

use Illuminate\Support\ServiceProvider;

class RouteSummaryProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Biscofil\LaravelRouteSummary\Commands\GetRouteSummary::class,
            ]);
        }
    }
}
