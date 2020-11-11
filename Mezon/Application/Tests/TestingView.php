<?php
namespace Mezon\Application\Tests;

use Mezon\Application\View;
use Mezon\HtmlTemplate\HtmlTemplate;

/**
 * View class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingView extends View
{

    public function __construct(?HtmlTemplate $template = null, string $viewName = 'default')
    {
        parent::__construct($template, $viewName);
    }

    public function viewTest(): string
    {
        return 'rendered content';
    }

    public function viewTest2(): string
    {
        return 'rendered content 2';
    }

    public function viewTest3(): string
    {
        return 'View rendered content';
    }
    
    public static $defaultViewWasRendered = false;

    public function viewDefault(): string
    {
        self::$defaultViewWasRendered = true;
        
        return 'Default';
    }

    public function viewMainFromConfig(): string
    {
        return 'Main From Config';
    }
}
