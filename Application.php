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

    /**
     * Constructor
     */
    function __construct()
    {
        // getting application's actions
        $this->router = new \Mezon\Router\Router();

        $this->router->fetchActions($this);
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
        $class = isset($route['class']) ? new $route['class']() : $this;
        $this->router->addRoute($route['route'], [
            $class,
            $route['callback']
        ], isset($route['method']) ? $route['method'] : 'GET');
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
     * @param string $path
     *            Path of the config for routes
     */
    public function loadRoutesFromConfig(string $path = './conf/routes.php'): void
    {
        if (file_exists($path)) {
            if (substr($path, - 5) === '.json') {
                // load config from json
                $routes = json_decode(file_get_contents($path), true);
            } else {
                // loadconfig from php
                $routes = (include ($path));
            }
            $this->loadRoutes($routes);
        } else {
            throw (new \Exception('Route ' . $path . ' was not found', 1));
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
        }
    }

    /**
     * Method redirects user to another page
     *
     * @param string $uRL
     *            New page
     */
    public function redirectTo($uRL): void
    {
        // @codeCoverageIgnoreStart
        header('Location: ' . $uRL);
        exit(0);
        // @codeCoverageIgnoreEnd
    }
}
