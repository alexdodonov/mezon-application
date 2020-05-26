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
     * @var \Mezon\HtmlTemplate\HtmlTemplate
     */
    private $template = false;

    /**
     * Constructor
     *
     * @param \Mezon\HtmlTemplate\HtmlTemplate $template
     *            Template
     */
    public function __construct(\Mezon\HtmlTemplate\HtmlTemplate $template)
    {
        parent::__construct();

        $this->template = $template;

        $this->getRouter()->setNoProcessorFoundErrorHandler([
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
     * @param \Mezon\Rest\Exception $e
     *            RestException object.
     */
    public function handleRestException(\Mezon\Rest\Exception $e): void
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
        } catch (\Mezon\Rest\Exception $e) {
            $this->handleRestException($e);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Getting template
     *
     * @return \Mezon\HtmlTemplate\HtmlTemplate Application's template
     * @codeCoverageIgnore
     */
    public function getTemplate(): \Mezon\HtmlTemplate\HtmlTemplate
    {
        return $this->template;
    }

    /**
     * Setting template
     *
     * @param \Mezon\HtmlTemplate\HtmlTemplate $template
     *            Template
     * @codeCoverageIgnore
     */
    public function setTemplate(\Mezon\HtmlTemplate\HtmlTemplate $template): void
    {
        $this->template = $template;
    }
}
