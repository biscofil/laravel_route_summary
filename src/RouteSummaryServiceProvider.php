<?php

namespace Biscofil\LaravelRouteSummary;

use Illuminate\Support\ServiceProvider;

class RouteSummaryProvider extends ServiceProvider
{

    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->commands([
            \Biscofil\LaravelRouteSummary\Commands\GetRouteSummary::class,
        ]);
    }
}
