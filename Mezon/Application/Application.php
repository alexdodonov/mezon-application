<?php
namespace Mezon\Application;

use Mezon\Transport\RequestParamsInterface;
use Mezon\Transport\HttpRequestParams;
use Mezon\Router\Router;
use Mezon\Transport\Request;
use Mezon\Redirect\Layer;
use Mezon\Utils\Fs;

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
    private $router;

    /**
     * Params fetcher
     *
     * @var ?HttpRequestParams
     */
    private $requestParams = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->router = new Router();
        Request::registerRouter($this->router);

        // getting application's actions
        $this->router->fetchActions($this);

        $classPath = Fs::getClassPath($this);

        if (file_exists($classPath . '/Conf/routes.php')) {
            $this->loadRoutesFromConfig($classPath . '/Conf/routes.php');
        }

        if (file_exists($classPath . '/Conf/routes.json')) {
            $this->loadRoutesFromConfig($classPath . '/Conf/routes.json');
        }
    }

    /**
     * Method returns $this->requestParams and creates this object if necessery
     *
     * @return RequestParamsInterface
     */
    public function getRequestParamsFetcher(): RequestParamsInterface
    {
        if ($this->requestParams === null) {
            $this->requestParams = new HttpRequestParams($this->getRouter());
        }

        return $this->requestParams;
    }

    /**
     * Method calls route and returns it's content
     *
     * @return mixed route processing result
     */
    protected function callRoute()
    {
        /** @var array<string, string> $_GET */
        return $this->getRouter()->callRoute(@$_GET['r']);
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

        if (! is_array($route['callback'])) {
            $class = isset($route['class']) ? new $route['class']() : $this;
            $callback = [
                $class,
                $callback
            ];
        }
        $this->getRouter()->addRoute($route['route'], $callback, isset($route['method']) ? $route['method'] : 'GET');
    }

    /**
     * Method loads routes
     *
     * @param array $routes
     *            list of routes
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
     *            path of the config for routes
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
            throw (new \Exception('Route "' . $configPath . '" was not found', 1));
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
     *            exception object to be formatted
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
            throw (new \Exception('Method ' . $method . ' was not found in the application ' . get_class($this), -1));
        }
    }

    /**
     * Method redirects user to another page
     *
     * @param string $url
     *            new page
     */
    public function redirectTo($url): void
    {
        /** @var array{retirect-to: string} $_GET */
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
        return $this->getRouter()->routeExists($route);
    }

    /**
     * Method returns router
     *
     * @return Router router
     */
    public function &getRouter(): Router
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
