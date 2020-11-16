<?php
namespace Mezon\Application;

/**
 * Interface PresenterInterface
 *
 * @package Application
 * @subpackage PresenterInterface
 * @author Dodonov A.A.
 * @version v.1.0 (2020/06/23)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base class for all presenters
 */
abstract class AbstractPresenter implements PresenterInterface
{

    /**
     * Presenter's name
     *
     * @var string
     */
    private $presenterName = '';

    /**
     * View object
     *
     * @var ViewInterface
     */
    private $view = null;

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
     * Method sets success message
     *
     * @var string
     */
    private $successMessage = '';

    /**
     * Constructor
     *
     * @param ?ViewInterface $view
     *            view object
     */
    public function __construct(?ViewInterface $view = null)
    {
        $this->view = $view;
    }

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function getPresenterName(): string
    {
        return $this->presenterName;
    }

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function setPresenterName(string $presenterName): void
    {
        $this->presenterName = $presenterName;
    }

    /**
     * Method returns code of the last error
     *
     * @return int code of the last error
     */
    public function getErrorCode(): int
    {
        return $this->view === null ? $this->errorCode : $this->view->getErrorCode();
    }

    /**
     * Method sets code of the last error
     *
     * @param int $code
     *            code of the last error
     */
    public function setErrorCode(int $errorCode): void
    {
        if ($this->view === null) {
            $this->errorCode = $errorCode;
        } else {
            $this->view->setErrorCode($errorCode);
        }
    }

    /**
     * Method return last error description
     *
     * @return string last error description
     */
    public function getErrorMessage(): string
    {
        return $this->view === null ? $this->errorMessage : $this->view->getErrorMessage();
    }

    /**
     * Method sets last error description
     *
     * @param
     *            string last error description
     */
    public function setErrorMessage(string $errorMessage): void
    {
        if ($this->view === null) {
            $this->errorMessage = $errorMessage;
        } else {
            $this->view->setErrorMessage($errorMessage);
        }
    }

    /**
     * Method return success message
     *
     * @return string success message
     */
    public function getSuccessMessage(): string
    {
        return $this->view === null ? $this->successMessage : $this->view->getSuccessMessage();
    }

    /**
     * Method sets success message
     *
     * @param $message string
     *            message
     */
    public function setSuccessMessage(string $successMessage): void
    {
        if ($this->view === null) {
            $this->successMessage = $successMessage;
        } else {
            $this->view->setSuccessMessage($successMessage);
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
    public function setViewParameter(string $name, $value, bool $setTemplateVar = true): void
    {
        if ($this->view !== null) {
            $this->view->setViewParameter($name, $value, $setTemplateVar);
        }
    }

    /**
     * Method gets view's var
     *
     * @param string $name
     *            var name
     * @return mixed view's variable
     */
    public function getViewParameter(string $name)
    {
        if ($this->view !== null) {
            return $this->view->getViewParameter($name);
        }

        return null;
    }
}
