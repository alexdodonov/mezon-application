<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Application\CommonApplication;
use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\View;

class CommonApplicationActionsUnitTest extends TestCase
{

    /**
     * Running with actions
     */
    public function testActionsJson()
    {
        // setup
        $_GET['r'] = 'from-config';
        $application = new TestCommonApplication();

        // test body
        ob_start();
        $application->run();
        $result = ob_get_flush();
        ob_clean();

        // assertions
        $this->assertStringContainsString('Some title', $result);
        $this->assertStringContainsString('Main From Config', $result);

        $this->assertTrue(TestingPresenter::$actionPresenterFromConfigWasCalled);
    }
}
