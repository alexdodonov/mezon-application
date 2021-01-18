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

        $this->getTemplate()->addPaths([
            __DIR__ . '/Res/'
        ]);
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
            'title' => 'Page title',
            'main' => new TestingView(new HtmlTemplate(__DIR__, 'index'), 'test3')
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

    protected function fileExists(string $fileName): bool
    {
        if ($this->hasMessages) {
            return parent::fileExists($fileName);
        } else {
            return false;
        }
    }
}
