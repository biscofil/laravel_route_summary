<?php

namespace Biscofil\LaravelRouteSummary;

use Biscofil\LaravelRouteSummary\Commands\GetRouteSummary;
use Illuminate\Support\ServiceProvider;

class RouteSummaryProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
               GetRouteSummary::class,
            ]);
        }
    }
}
