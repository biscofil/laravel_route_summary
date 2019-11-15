<?php

namespace Biscofil\LaravelRouteSummary\Commands;

class GetRouteSummary extends Command
{

    protected $signature = 'route:summary';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        dd("OOOOK");
    }
}
