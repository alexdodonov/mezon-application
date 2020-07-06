<?php
namespace Mezon\Application;

use Mezon\Transport\RequestParamsInterface;

/**
 * Class Presenter
 *
 * @package Mezon
 * @subpackage Presenter
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/12)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base class for all views
 *
 * @deprecated since 2020-06-26
 */
class Presenter extends \Mezon\Application\AbstractPresenter
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
     * @param string $presenterName
     *            Presenter name to be executed
     * @param ?\Mezon\Transport\RequestParams $requestParams
     *            request params fetcher
     */
    public function __construct(string $presenterName = '', ?\Mezon\Transport\RequestParams $requestParams = null)
    {
        $this->setPresenterName($presenterName);

        $this->requestParams = $requestParams;
    }

    /**
     * Method return $requestParams and thrown exception if it was not set
     *
     * @return RequestParamsInterface request params fetcher
     * @deprecated since 2020-07-06 use getRequestParamsFetcher
     * @codeCoverageIgnore
     */
    public function getParamsFetcher(): RequestParamsInterface
    {
        return $this->getRequestParamsFetcher();
    }

    /**
     * Method returns $this->requestParams and creates this object if necessery
     *
     * @return RequestParamsInterface
     */
    public function getRequestParamsFetcher(): RequestParamsInterface
    {
        if ($this->requestParams === null) {
            throw (new \Exception('Param fetcher was not setup'));
        }

        return $this->requestParams;
    }

    /**
     * Method runs controller
     *
     * @param
     *            string PresenterName
     *            Presenter name to be run
     * @return mixed result of the controller
     */
    public function run(string $presenterName = '')
    {
        if ($presenterName === '') {
            $presenterName = $this->getPresenterName();
        }

        if ($presenterName === '') {
            $presenterName = 'Default';
        }

        if (method_exists($this, 'presenter' . $presenterName)) {
            return call_user_func([
                $this,
                'presenter' . $presenterName
            ]);
        }

        throw (new \Exception('Presenter ' . $presenterName . ' was not found'));
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
}
