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
        $application = new TestCommonApplication();

        // test body
        $result = $application->actionFromConfig();

        // assertions
        $this->assertArrayHasKey('title', $result);
        $this->assertEquals('Some title', $result['title']);

        $this->assertArrayHasKey('main', $result);
        $this->assertInstanceOf(View::class, $result['main']);

        $this->assertTrue(TestingPresenter::$actionPresenterFromConfigWasCalled);
    }
}
