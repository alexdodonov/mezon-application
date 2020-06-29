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
