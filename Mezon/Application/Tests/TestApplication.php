<?php
namespace Mezon\Application\Tests;

/**
 * Application for testing purposes.
 */
class TestApplication extends \Mezon\Application\Application
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
}
