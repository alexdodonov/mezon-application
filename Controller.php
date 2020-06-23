<?php
namespace Mezon\Application;

/**
 * Class Controller
 *
 * @package Mezon
 * @subpackage Controller
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/12)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base class for all views
 *
 * @deprecated since 2020-06-26
 */
class Controller extends \Mezon\Application\ControllerInterface
{

    /**
     * Router
     *
     * @var \Mezon\Transport\RequestParams
     */
    private $requestParams = null;

    /**
     * Constructor
     *
     * @param string $controllerName
     *            Controller name to be executed
     * @param ?\Mezon\Transport\RequestParams $requestParams
     *            request params fetcher
     */
    public function __construct(string $controllerName = '', ?\Mezon\Transport\RequestParams $requestParams = null)
    {
        $this->setControllerName($controllerName);

        $this->requestParams = $requestParams;
    }

    /**
     * Method runs controller
     *
     * @param
     *            string ControllerName
     *            Controller name to be run
     * @return mixed result of the controller
     */
    public function run(string $controllerName = '')
    {
        if ($controllerName === '') {
            $controllerName = $this->getControllerName();
        }

        if ($controllerName === '') {
            $controllerName = 'Default';
        }

        if (method_exists($this, 'controller' . $controllerName)) {
            return call_user_func([
                $this,
                'controller' . $controllerName
            ]);
        }

        throw (new \Exception('Controller ' . $controllerName . ' was not found'));
    }

    /**
     * May be these functions should be excluded to base class common with View
     */

    /**
     * Method redirects user to another page
     *
     * @param string $url
     * @codeCoverageIgnore
     */
    public function redirectTo(string $url): void
    {
        header("Location: $url");
        exit(0);
    }

    /**
     * Method builds route data
     *
     * @param string $route
     *            route
     * @param string $method
     *            HTTP method
     * @param string $function
     *            controller's function name
     * @return array built route data
     */
    public function buildRoute(string $route, string $method, string $function): array
    {
        return [
            'route' => $route,
            'method' => $method,
            'callback' => [
                $this,
                $function
            ]
        ];
    }

    /**
     * Method returns code of the last error
     *
     * @return int code of the last error
     * @codeCoverageIgnore
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Method sets code of the last error
     *
     * @param int $code
     *            code of the last error
     * @codeCoverageIgnore
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * Method return last error description
     *
     * @return string last error description
     * @codeCoverageIgnore
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Method sets last error description
     *
     * @param
     *            string last error description
     * @codeCoverageIgnore
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
}
