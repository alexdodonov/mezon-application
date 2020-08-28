<?php
namespace Mezon\Application\Tests;

use Mezon\Application\View;

/**
 * View class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingView extends View
{

    public function viewTest(): string
    {
        return 'rendered content';
    }

    public function viewTest2(): string
    {
        return 'rendered content 2';
    }
}
