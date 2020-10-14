<?php

namespace Mezon\Application\Tests;

/**
 * Application for testing purposes.
 */
class TestApplication extends \Mezon\Application\Application
{
    
    function actionExisting()
    {
        /* existing action */
        return 'OK!';
    }
    
    function compound()
    {
        return 'compond';
    }
}
