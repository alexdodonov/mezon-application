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
     * Method sets code of the last error
     *
     * @param int $code
     *            code of the last error
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;

        $this->getTemplate()->setPageVar(ViewBase::ERROR_CODE, $errorCode);
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

        $this->getTemplate()->setPageVar(ViewBase::ERROR_MESSAGE, $errorMessage);
    }
}
