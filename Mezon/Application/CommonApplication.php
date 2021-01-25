<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Rest;
use Mezon\Router\Utils;

// TODO fetch CommonApplication in separate package
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
 * To load routes from the config call $this->load_routes_from_config('./Conf/routes.json');
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
class CommonApplication extends Application
{

    /**
     * Application's template
     *
     * @var HtmlTemplate
     */
    private $template = false;

    /**
     * Constructor
     *
     * @param HtmlTemplate $template
     *            Template
     */
    public function __construct(HtmlTemplate $template)
    {
        parent::__construct();

        $this->template = $template;

        $this->getRouter()->setNoProcessorFoundErrorHandler([
            $this,
            'noRouteFoundErrorHandler'
        ]);

        $this->loadActoinsFromConfig();
    }

    /**
     * Method handles 404 errors
     *
     * @codeCoverageIgnore
     */
    public function noRouteFoundErrorHandler(): void
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
     * @param Rest\Exception $e
     *            RestException object.
     */
    public function handleRestException(Rest\Exception $e): void
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
     * Method sets GET parameter as template var
     *
     * @param string $fieldName
     *            name of the GET parameter
     */
    protected function setGetVar(string $fieldName): void
    {
        if (isset($_GET[$fieldName])) {
            $this->template->setPageVar($fieldName, $_GET[$fieldName]);
        }
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
                    $content = $value instanceof ViewInterface ? $value->render() : $value;

                    $this->template->setPageVar($key, $content);
                }
            }

            $this->setGetVar('redirect-to');

            print($this->template->compile());
        } catch (Rest\Exception $e) {
            $this->handleRestException($e);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Getting template
     *
     * @return HtmlTemplate Application's template
     * @codeCoverageIgnore
     */
    public function getTemplate(): HtmlTemplate
    {
        return $this->template;
    }

    /**
     * Setting template
     *
     * @param HtmlTemplate $template
     *            Template
     * @codeCoverageIgnore
     */
    public function setTemplate(HtmlTemplate $template): void
    {
        $this->template = $template;
    }

    /**
     * Does file exists
     *
     * @param string $fileName
     *            file name
     * @return bool true if the file exists, false otherwise
     */
    protected function fileExists(string $fileName): bool
    {
        return $this->getTemplate()->fileExists($fileName);
    }

    /**
     * Method returns localized error message by it's key
     *
     * @param string $actionMessageCode
     *            key of the message
     * @return string localized error message by it's key
     */
    protected function getActionMessage(string $actionMessageCode): string
    {
        if ($this->fileExists('action-messages.json')) {
            $messages = $this->getTemplate()->getFile('action-messages.json');
            $messages = json_decode($messages, true);

            if (isset($messages[$actionMessageCode])) {
                return $messages[$actionMessageCode];
            } else {
                throw (new \Exception('The message with locator "' . $actionMessageCode . '" was not found', - 1));
            }
        }

        return '';
    }

    /**
     * Method returns action-message or '' if not set
     *
     * @return string
     */
    protected function getActionMessageCode(): string
    {
        return $_GET['action-message'] ?? '';
    }

    /**
     * Method sets message variable
     *
     * @param string $successMessageLocator
     *            message code
     */
    public function setSuccessMessage(string $successMessageLocator): void
    {
        $this->getTemplate()->setPageVar('action-message', $this->getActionMessage($successMessageLocator));
    }

    /**
     * Method sets message variable
     *
     * @param string $errorMessageLocator
     *            message code
     */
    public function setErrorMessage(string $errorMessageLocator): void
    {
        $this->getTemplate()->setPageVar('action-message', $this->getActionMessage($errorMessageLocator));
    }

    /**
     * Method compiles result record
     *
     * @param mixed $presenter
     *            main area presenter
     * @return array result record
     */
    public function result($presenter = null): void
    {
        if ($presenter !== null) {
            $presenter->run();
        }

        if ($actionMessage = $this->getActionMessageCode()) {
            $this->setSuccessMessage($actionMessage);
        }
    }

    /**
     * Method creates action from JSON config
     *
     * @param string $path
     *            path to JSON config
     */
    private function createActionFromJsonConfig(string $path): void
    {
        $method = 'action' . basename($path, '.json');

        $this->$method = function () use ($path): array {
            $result = [];

            // TODO extract method and put in the ActionBuilder
            $presenter = null;
            $config = json_decode(file_get_contents($path), true);
            $views = [];

            ActionBuilder::constructOverrideHandler($path, $config);

            foreach ($config as $key => $value) {
                $callback = ActionBuilder::getActionBuilderMethod($this, $key, $value);

                if ($callback !== null) {
                    $callback();
                } elseif (is_string($value)) {
                    // string content
                    $result[$key] = $value;
                } elseif ($key === 'presenter') {
                    $presenter = new $value['class'](
                        isset($value['view']) && isset($views[$value['view']]) ? $views[$value['view']] : null,
                        $value['name'],
                        $this->getRequestParamsFetcher());
                } else {
                    ActionBuilder::constructOtherView($this, $result, $key, $value, $views);
                }
            }

            $this->result($presenter);

            return $result;
        };

        $this->loadRoute(
            [
                'route' => Utils::convertMethodNameToRoute($method),
                'callback' => [
                    $this,
                    $method
                ],
                'method' => [
                    'GET',
                    'POST'
                ]
            ]);
    }

    /**
     * Method loads actions from path
     *
     * @param string $path
     */
    protected function loadActionsFromDirectory(string $path): void
    {
        // TODO make this method public, so we can call it in CabApplication tests
        if (file_exists($path)) {
            $files = scandir($path);

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    // do nothing
                } elseif (is_file($path . '/' . $file) && strpos($file, '.json') !== false) {
                    $this->createActionFromJsonConfig($path . '/' . $file);
                } elseif (is_dir($path . '/' . $file)) {
                    $this->loadActionsFromDirectory($path . '/' . $file);
                }
            }
        }
    }

    /**
     * Method loads all actions from ./actions directory
     */
    private function loadActoinsFromConfig(): void
    {
        $this->loadActionsFromDirectory($this->getClassPath() . '/Actions/');
    }
}

