<?php

namespace Biscofil\LaravelRouteSummary\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\File;
use ReflectionParameter;

class GetRouteSummary extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $routes = $this->getRoutes();

        $baseFolder = base_path('route_summary');

        $htmlFilePath = $baseFolder . '/routes.html';
        $jsonFilePath = $baseFolder . '/routes.json';

        if (!file_exists($baseFolder)) {
            mkdir($baseFolder);
        } else {
            if (file_exists($htmlFilePath)) {
                unlink($htmlFilePath);
            }
            if (file_exists($jsonFilePath)) {
                unlink($jsonFilePath);
            }
        }

        file_put_contents($jsonFilePath, json_encode($routes, JSON_PRETTY_PRINT));

        File::put(
            $htmlFilePath,
            view('route-summary::index')
                ->render()
        );

        $this->info("Html file saved to " . $htmlFilePath);
    }

    /**
     * Returns an array containing data of all routes
     */
    public function getRoutes()
    {

        $app = app();
        $routes = $app->routes->getRoutes();

        $out = array_map(function (Route $route) {

            //middleware
            $middleware = $route->gatherMiddleware();
            $middleware = array_map(function ($middleware) {

                if (strpos($middleware, ':') !== false) {
                    //parameters

                    $middlewareParts = preg_split('/:/', $middleware);

                    $middlewareName = array_shift($middlewareParts);

                    $params = preg_split('/,/', $middlewareParts[0]);

                    return [
                        'middleware' => $middlewareName,
                        'params' => $params
                    ];
                } else {
                    return $middleware;
                }
            }, $middleware);

            $controllerParts = preg_split('/@/', $route->getAction()['controller']);

            $controllerName = $controllerParts[0];
            $controllerMethod = $controllerParts[1];

            $parameters = array_merge(array_map(function ($parameterName) use ($controllerMethod, $controllerName) {
                $p = new ReflectionParameter([$controllerName, $controllerMethod], $parameterName);
                return [
                    $parameterName => $p->getType()
                ];
            }, $route->parameterNames()));

            // ############ METHODS

            $httpMethods = $route->methods();

            //remove HEAD
            if (($key = array_search('HEAD', $httpMethods)) !== FALSE) {
                unset($httpMethods[$key]);
            }

            return [
                'uri' => $route->uri,
                'controller' => $controllerName,
                'controller_method' => $controllerMethod,
                'parameters' => $parameters, //$route->getCompiled()->getPathVariables(),
                'methods' => $httpMethods,
                'middleware' => $middleware
            ];
        }, $routes);

        return $out;
    }
}
