<?php

namespace Biscofil\LaravelRouteSummary\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use ReflectionParameter;

class GetRouteSummary extends Command
{

    const GetRouteSummarySuccess = 0;
    const GetRouteSummaryFailure = 1;

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
     */
    public function handle()
    {
        try {

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

            $content = view('route-summary::index', ['routes' => $routes]);
            $content = $content->render();
            file_put_contents($htmlFilePath, $content);

            $this->info("Json file saved to " . $jsonFilePath);
            $this->info("Html file saved to " . $htmlFilePath);

            return self::GetRouteSummarySuccess;

        } catch (\Exception $exception) {

            $this->error($exception->getMessage());

        }

        return self::GetRouteSummaryFailure;
    }

    /**
     * Returns an array containing data of all routes
     */
    public function getRoutes(): array
    {

        $app = app();
        $routes = $app->routes->getRoutes();

        return array_map(function (Route $route) {

            $this->info($route->uri);

            try {


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

                // ######## Parameters

                $parameters = array_map(function ($parameterName) use ($controllerMethod, $controllerName) {
                    $p = new ReflectionParameter([$controllerName, $controllerMethod], $parameterName);

                    if (is_null($p->getType())) {
                        return [$parameterName => 'UNKNOWN'];
                    } else {
                        return [$parameterName => $p->getType()->getName()];
                    }

                }, $route->parameterNames());

                if (count($parameters) > 0) {
                    $parameters = array_merge(...$parameters);
                }

                // ############ METHODS

                $httpMethods = $route->methods();

                //remove HEAD
                if (($key = array_search('HEAD', $httpMethods)) !== FALSE) {
                    unset($httpMethods[$key]);
                }

                return [
                    'uri' => $route->uri,
                    'name' => $route->getName(),
                    'controller' => $controllerName,
                    'controller_method' => $controllerMethod,
                    'parameters' => $parameters, //$route->getCompiled()->getPathVariables(),
                    'methods' => $httpMethods,
                    'middleware' => $middleware
                ];


            } catch (\InvalidArgumentException $exception) {

                $this->error($exception->getMessage());

                dd($route->uri);

                return null;
            }

        }, $routes);
    }
}
