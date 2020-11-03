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
 * Base interface for all presenters
 */
interface PresenterInterface
{

    /**
     * Method runs presenter
     *
     * @param string $presenterName
     *            Presenter name to be run
     * @return mixed Presenter execution result
     */
    public function run(string $presenterName = '');

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function getPresenterName(): string;

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function setPresenterName(string $presenterName): void;

    /**
     * Method returns code of the last error
     *
     * @return int code of the last error
     */
    public function getErrorCode(): int;

    /**
     * Method sets code of the last error
     *
     * @param int $code
     *            code of the last error
     * @codeCoverageIgnore
     */
    public function setErrorCode(int $errorCode): void;

    /**
     * Method return last error description
     *
     * @return string last error description
     */
    public function getErrorMessage(): string;

    /**
     * Method sets last error description
     *
     * @param
     *            string last error description
     * @codeCoverageIgnore
     */
    public function setErrorMessage(string $errorMessage): void;

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
    public function setViewParameter(string $name, $value, bool $setTemplateVar);

    /**
     * Method gets view's var
     *
     * @param string $name
     *            var name
     * @return mixed view's variable
     */
    public function getViewParameter(string $name);
}
