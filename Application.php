<?php
namespace Mezon\Application;

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
     */
    protected $router = null;

    const DEFAULT_PHP_ROUTES_PATH = './conf/routes.php';

    const DEFAULT_JSON_ROUTES_PATH = './conf/routes.json';

    /**
     * Constructor
     */
    function __construct()
    {
        // getting application's actions
        $this->router = new \Mezon\Router\Router();

        $this->router->fetchActions($this);

        if (file_exists(CommonApplication::DEFAULT_PHP_ROUTES_PATH)) {
            $this->loadRoutesFromConfig(CommonApplication::DEFAULT_PHP_ROUTES_PATH);
        }

        if (file_exists(CommonApplication::DEFAULT_JSON_ROUTES_PATH)) {
            $this->loadRoutesFromConfig(CommonApplication::DEFAULT_JSON_ROUTES_PATH);
        }
    }

    /**
     * Method calls route and returns it's content
     */
    protected function callRoute()
    {
        $route = explode('/', trim(@$_GET['r'], '/'));

        if ($this->router === null) {
            throw (new \Exception('this->Router was not set', - 2));
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
    public function loadRoutesFromConfig(string $configPath = CommonApplication::DEFAULT_PHP_ROUTES_PATH): void
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
     * Method processes exception
     *
     * @param \Exception $e
     *            Exception object to be formatted
     */
    public function handleException(\Exception $e): void
    {
        print('<pre>' . $e);
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
        // @codeCoverageIgnoreStart
        header('Location: ' . $url);
        exit(0);
        // @codeCoverageIgnoreEnd
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
}
