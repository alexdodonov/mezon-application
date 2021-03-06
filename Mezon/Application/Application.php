<?php
namespace Mezon\Application;

use Mezon\Transport\RequestParamsInterface;
use Mezon\Transport\HttpRequestParams;
use Mezon\Router\Router;
use Mezon\Transport\Request;
use Mezon\Redirect\Layer;

/**
 * Class Application
 *
 * @package Mezon
 * @subpackage Application
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/13)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class of the application
 */
class Application
{

    /**
     * Router object
     *
     * @var Router
     */
    private $router = null;

    /**
     * Params fetcher
     *
     * @var HttpRequestParams
     */
    private $requestParams = null;

    /**
     * Constructor
     */
    function __construct()
    {
        // getting application's actions
        $this->router = new Router();
        Request::registerRouter($this->router);

        $this->router->fetchActions($this);

        $classPath = $this->getClassPath();

        if (file_exists($classPath . '/Conf/routes.php')) {
            $this->loadRoutesFromConfig($classPath . '/Conf/routes.php');
        }

        if (file_exists($classPath . '/Conf/routes.json')) {
            $this->loadRoutesFromConfig($classPath . '/Conf/routes.json');
        }
    }

    /**
     * Method returns class path
     *
     * @return string
     */
    protected function getClassPath(): string
    {
        $reflector = new \ReflectionClass(get_class($this));
        return dirname($reflector->getFileName());
    }

    /**
     * Method returns $this->requestParams and creates this object if necessery
     *
     * @return RequestParamsInterface
     */
    public function getRequestParamsFetcher(): RequestParamsInterface
    {
        if ($this->requestParams === null) {
            $this->requestParams = new HttpRequestParams($this->router);
        }

        return $this->requestParams;
    }

    /**
     * Method calls route and returns it's content
     */
    protected function callRoute()
    {
        $route = explode('/', trim(@$_GET['r'], '/'));

        if ($this->router === null) {
            throw (new \Exception('this->router was not set', - 2));
        }

        return $this->router->callRoute($route);
    }

    /**
     * Method loads single route
     *
     * @param array $route
     *            Route settings
     */
    public function loadRoute(array $route): void
    {
        if (isset($route['route']) === false) {
            throw (new \Exception('Field "route" must be set'));
        }
        if (isset($route['callback']) === false) {
            throw (new \Exception('Field "callback" must be set'));
        }

        $callback = $route['callback'];

        if (is_array($route['callback'])) {
            $route['class'] = $route['callback'][0];
            $route['callback'] = $route['callback'][1];
        } else {
            $class = isset($route['class']) ? new $route['class']() : $this;
            $callback = [
                $class,
                $callback
            ];
        }
        $this->router->addRoute($route['route'], $callback, isset($route['method']) ? $route['method'] : 'GET');
    }

    /**
     * Method loads routes
     *
     * @param array $routes
     *            List of routes
     */
    public function loadRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $this->loadRoute($route);
        }
    }

    /**
     * Method loads routes from config file in *.php or *.json format
     *
     * @param string $configPath
     *            Path of the config for routes
     */
    public function loadRoutesFromConfig(string $configPath): void
    {
        if (file_exists($configPath)) {
            if (substr($configPath, - 5) === '.json') {
                // load config from json
                $routes = json_decode(file_get_contents($configPath), true);
            } else {
                // loadconfig from php
                $routes = (include ($configPath));
            }
            $this->loadRoutes($routes);
        } else {
            throw (new \Exception('Route ' . $configPath . ' was not found', 1));
        }
    }

    /**
     * Method loads list of configs
     *
     * @param array $configPaths
     *            paths to config files
     */
    public function loadRoutesFromConfigs(array $configPaths): void
    {
        foreach ($configPaths as $configPath) {
            $this->loadRoutesFromConfig($configPath);
        }
    }

    /**
     * Method loads routes from the directory
     *
     * @param string $directory
     *            path to the directory. Scanninng is recursive.
     */
    public function loadRoutesFromDirectory(string $directory): void
    {
        $paths = scandir($directory);

        foreach ($paths as $path) {
            if (is_file($directory . '/' . $path)) {
                $this->loadRoutesFromConfig($directory . '/' . $path);
            }
        }
    }

    /**
     * Method processes exception
     *
     * @param \Exception $e
     *            Exception object to be formatted
     */
    public function handleException(\Exception $e): void
    {
        print('<pre>' . $e->getMessage() . '<br/>' . implode('<br/>', $this->formatCallStack($e)));
    }

    /**
     * Running application
     */
    public function run(): void
    {
        try {
            print($this->callRoute());
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Allowing to call methods added on the fly
     *
     * @param string $method
     *            Method to be called
     * @param array $args
     *            Arguments
     * @return mixed Result of the call
     */
    public function __call(string $method, array $args)
    {
        if (isset($this->$method)) {
            $function = $this->$method;

            return call_user_func_array($function, $args);
        } else {
            throw (new \Exception('Method ' . $method . ' was not found in the application ' . get_class($this)));
        }
    }

    /**
     * Method redirects user to another page
     *
     * @param string $url
     *            New page
     */
    public function redirectTo($url): void
    {
        if (isset($_GET['redirect-to'])) {
            $url = str_replace('{redirect-to}', urldecode($_GET['redirect-to']), $url);
        }

        Layer::redirectTo($url);
    }

    /**
     * Method validates that route exists
     *
     * @param string $route
     *            route
     * @return bool true if the route exists
     */
    public function routeExists(string $route): bool
    {
        return $this->router->routeExists($route);
    }

    /**
     * Method returns router
     *
     * @return \Mezon\Router\Router router
     */
    public function getRouter(): \Mezon\Router\Router
    {
        return $this->router;
    }

    /**
     * Formatting call stack
     *
     * @param mixed $e
     *            Exception object
     */
    protected function formatCallStack($e): array
    {
        $stack = $e->getTrace();

        foreach ($stack as $i => $call) {
            $stack[$i] = (@$call['file'] == '' ? 'lambda : ' : @$call['file'] . ' (' . $call['line'] . ') : ') .
                (@$call['class'] == '' ? '' : $call['class'] . '->') . $call['function'];
        }

        return array_reverse($stack);
    }

    /**
     * Method builds route data
     *
     * @param string $route
     *            route
     * @param string $method
     *            HTTP method
     * @param object $handler
     *            object wich handles request
     * @param string $function
     *            controller's function name
     * @return array built route data
     */
    public static function buildRoute(string $route, string $method, object $handler, string $function): array
    {
        return [
            'route' => $route,
            'method' => $method,
            'callback' => [
                $handler,
                $function
            ]
        ];
    }
}
