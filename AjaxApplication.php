<?php
namespace Mezon\Application;

/**
 * Class AjaxApplication
 *
 * @package Application
 * @subpackage AjaxApplication
 * @author Dodonov A.A.
 * @version v.1.0 (2019/09/27)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class of the ajax-application
 */
abstract class AjaxApplication extends \Mezon\Application\Application
{

    use \Mezon\Application\AjaxMethodsTrait;

    /**
     * Method processes exception.
     *
     * @param \Exception $e
     *            Exception object.
     */
    public function handleException(\Exception $e): void
    {
        $error = new \stdClass();
        $error->message = $e->getMessage();
        $error->code = $e->getCode();
        if (isset($e->HTTPBody)) {
            $error->httpBody = $e->HTTPBody;
        }
        $error->call_stack = $this->formatCallStack($e);
        $error->host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        print(json_encode($error, JSON_PRETTY_PRINT));
    }
}
