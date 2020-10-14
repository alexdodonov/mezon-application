<?php
namespace Mezon\Application\Tests;

use Mezon\Rest;
use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\CommonApplication;

/**
 * Application for testing purposes.
 */
class TestCommonApplication extends CommonApplication
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(new HtmlTemplate(__DIR__, 'index'));
    }

    function actionArrayResult(): array
    {
        return [
            'title' => 'Array result',
            'main' => 'Route main'
        ];
    }

    function actionViewResult(): array
    {
        return [
            'title' => 'View result',
            'main' => new TestView('Test view result')
        ];
    }

    function actionInvalid(): string
    {
        return 'Invalid';
    }

    function actionRest(): array
    {
        throw (new Rest\Exception('exception', - 1, 502, 'body'));
    }

    function redirectTo($url): void
    {
        // do nothing
    }

    public $hasMessages = true;

    protected function getClassPath(): string
    {
        if ($this->hasMessages) {
            return parent::getClassPath();
        } else {
            return './unexisting-path';
        }
    }
}
