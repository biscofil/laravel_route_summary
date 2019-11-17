<?php

namespace Biscofil\LaravelRouteSummary;

use Biscofil\LaravelRouteSummary\Commands\GetRouteSummary;
use Illuminate\Support\ServiceProvider;

class RouteSummaryProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'route-summary');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GetRouteSummary::class,
            ]);
        }
    }


    /**
     * Register the API doc commands.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
