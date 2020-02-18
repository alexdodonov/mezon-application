<?php
namespace Mezon\Application;

/**
 * Class CommonApplication
 *
 * @package Mezon
 * @subpackage CommonApplication
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Common application with any available template
 *
 * To load routes from the config call $this->load_routes_from_config('./conf/routes.json');
 *
 * The format of the *.json config must be like this:
 *
 * [
 * {
 * "route" : "/route1" ,
 * "callback" : "callback1" ,
 * "method" : "POST"
 * } ,
 * {
 * "route" : "/route2" ,
 * "callback" : "callback2" ,
 * "method" : ["GET" , "POST"]
 * }
 * ]
 */
class CommonApplication extends \Mezon\Application\Application
{

    /**
     * Application's template
     *
     * @var \Mezon\Application\HtmlTemplate
     */
    protected $template = false;

    /**
     * Constructor
     *
     * @param \Mezon\Application\HtmlTemplate $template
     *            Template
     */
    public function __construct(\Mezon\Application\HtmlTemplate $template)
    {
        parent::__construct();

        $this->template = $template;

        $this->router->setNoProcessorFoundErrorHandler([
            $this,
            'noRouteFoundErrorHandler'
        ]);
    }

    /**
     * Method handles 404 errors
     *
     * @param string $route
     * @codeCoverageIgnore
     */
    public function noRouteFoundErrorHandler(string $route): void
    {
        $this->redirectTo('/404');
    }

    /**
     * Method renders common parts of all pages.
     *
     * @return array List of common parts.
     */
    public function crossRender(): array
    {
        return [];
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

        return $stack;
    }

    /**
     * Method formats exception object
     *
     * @param \Exception $e
     *            Exception
     * @return object Formatted exception object
     */
    protected function baseFormatter(\Exception $e): object
    {
        $error = new \stdClass();
        $error->message = $e->getMessage();
        $error->code = $e->getCode();
        $error->call_stack = $this->formatCallStack($e);
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['REQUEST_URI']) {
            $error->host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $error->host = 'undefined';
        }
        return $error;
    }

    /**
     * Method processes exception.
     *
     * @param \Mezon\Service\ServiceRestTransport\RestException $e
     *            RestException object.
     */
    public function handleRestException(\Mezon\Service\ServiceRestTransport\RestException $e): void
    {
        $error = $this->baseFormatter($e);

        $error->httpBody = $e->getHttpBody();

        print('<pre>' . json_encode($error, JSON_PRETTY_PRINT));
    }

    /**
     * Method processes exception.
     *
     * @param \Exception $e
     *            Exception object.
     */
    public function handleException(\Exception $e): void
    {
        $error = $this->baseFormatter($e);

        print('<pre>' . json_encode($error, JSON_PRETTY_PRINT));
    }

    /**
     * Running application.
     */
    public function run(): void
    {
        try {
            $callRouteResult = $this->callRoute();
            if (is_array($callRouteResult) === false) {
                throw (new \Exception('Route was not called properly'));
            }

            $result = array_merge($callRouteResult, $this->crossRender());

            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    $content = $value instanceof \Mezon\Application\ViewInterface ? $value->render() : $value;

                    $this->template->setPageVar($key, $content);
                }
            }

            print($this->template->compile());
        } catch (\Mezon\Service\ServiceRestTransport\RestException $e) {
            // TODO exclude RestException to separate package
            $this->handleRestException($e);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Getting template
     *
     * @return \Mezon\Application\HtmlTemplate Application's template
     * @codeCoverageIgnore
     */
    public function getRemplate(): \Mezon\Application\HtmlTemplate
    {
        return $this->template;
    }

    /**
     * Setting template
     *
     * @param \Mezon\Application\HtmlTemplate $template
     *            Template
     * @codeCoverageIgnore
     */
    public function setTemplate(\Mezon\Application\HtmlTemplate $template): void
    {
        $this->template = $template;
    }
}
