<?php
namespace Mezon\Application;

/**
 * Class AjaxMethodsTrait
 *
 * @package Application
 * @subpackage AjaxMethodsTrait
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/27)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class of the ajax-application
 */
trait AjaxMethodsTrait
{

    /**
     * Validating authorization for ajax requests
     *
     * Must be overriden in the base class
     */
    abstract protected function validateAuthorizationForAjaxRequests();

    /**
     * Method finishes ajax requests processing
     */
    protected function ajaxRequestSuccess()
    {
        print(json_encode([
            "code" => 0
        ]));

        exit(0);
    }

    /**
     * Method finishes ajax requests processing and returns result
     */
    protected function ajaxRequestResult($result)
    {
        print(json_encode($result));

        exit(0);
    }

    /**
     * Method finishes ajax requests processing
     *
     * @param string $message
     *            Error message
     * @param int $code
     *            Error code
     */
    protected function ajaxRequestError(string $message, int $code = - 1)
    {
        print(json_encode([
            "message" => $message,
            "code" => $code
        ]));

        exit(0);
    }
}
