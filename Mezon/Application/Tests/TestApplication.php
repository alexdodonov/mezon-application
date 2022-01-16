<?php
namespace Mezon\Application\Tests;

use Mezon\Transport\Request;
use Mezon\Application\Application;

/**
 * Application for testing purposes.
 */
class TestApplication extends Application
{

    function actionExisting(): string
    {
        /* existing action */
        return 'OK!';
    }

    function compound(): string
    {
        return 'compond';
    }

    function paramRoute(): string
    {
        return Request::getParam('id');
    }
}
