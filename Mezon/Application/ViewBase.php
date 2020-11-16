<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;

/**
 * Class ViewBase
 *
 * @package Mezon
 * @subpackage View
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/06)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class for all views
 */
abstract class ViewBase implements \Mezon\Application\ViewInterface
{

    /**
     * Template variable
     *
     * @var string
     */
    const ERROR_CODE = 'error-code';

    /**
     * Template variable
     *
     * @var string
     */
    const ERROR_MESSAGE = 'error-message';

    /**
     * Template variable
     *
     * @var string
     */
    const SUCCESS_MESSAGE = 'success-message';

    /**
     * Active template
     *
     * @var HtmlTemplate
     */
    private $template = null;

    /**
     * Error code
     *
     * @var integer
     */
    private $errorCode = 0;

    /**
     * Error message
     *
     * @var string
     */
    private $errorMessage = '';

    /**
     * Success message
     *
     * @var string
     */
    private $successMessage = '';

    /**
     * View variables
     *
     * @var array
     */
    private $variables = [];

    /**
     * Constructor
     *
     * @param HtmlTemplate $template
     *            template
     */
    public function __construct(HtmlTemplate $template = null)
    {
        $this->template = $template;
    }

    /**
     * Method returns template
     *
     * @return HtmlTemplate template
     */
    public function getTemplate(): HtmlTemplate
    {
        if ($this->template === null) {
            throw (new \Exception('Template was not set for the view', - 1));
        }

        return $this->template;
    }

    /**
     * Method returns code of the last error
     *
     * @return int code of the last error
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Method checks if the template was setup
     *
     * @return bool true if the method was setup, false otherwise
     */
    public function templateWasSetup(): bool
    {
        return $this->template !== null;
    }

    /**
     * Method sets code of the last error
     *
     * @param int $code
     *            code of the last error
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;

        if ($this->templateWasSetup()) {
            $this->getTemplate()->setPageVar(ViewBase::ERROR_CODE, $errorCode);
        }
    }

    /**
     * Method return last error description
     *
     * @return string last error description
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
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;

        if ($this->templateWasSetup()) {
            $this->getTemplate()->setPageVar(ViewBase::ERROR_MESSAGE, $errorMessage);
        }
    }

    /**
     * Method return success message
     *
     * @return string success message
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * Method sets success message
     *
     * @param string $successMessage
     *            success message
     */
    public function setSuccessMessage(string $successMessage): void
    {
        $this->successMessage = $successMessage;

        if ($this->templateWasSetup()) {
            $this->getTemplate()->setPageVar(ViewBase::SUCCESS_MESSAGE, $successMessage);
        }
    }

    /**
     * Method sets view's var
     *
     * @param string $name
     *            var name
     * @param mixed $value
     *            var value
     * @param bool $setTemplateVar
     *            do we need to set template parameter
     */
    public function setViewParameter(string $name, $value, bool $setTemplateVar): void
    {
        if ($this->template !== null && $setTemplateVar) {
            $this->template->setPageVar($name, $value);
        }

        $this->variables[$name] = $value;
    }

    /**
     * Method sets view's var
     *
     * @param string $name
     *            var name
     * @return mixed view's variable value
     */
    public function getViewParameter(string $name)
    {
        return $this->variables[$name] ?? null;
    }
}
