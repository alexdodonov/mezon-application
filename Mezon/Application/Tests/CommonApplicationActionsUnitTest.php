<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Application\CommonApplication;
use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\View;

class CommonApplicationActionsUnitTest extends TestCase
{

    /**
     * Data provider for the test testActionsJson
     *
     * @return array testing data
     */
    public function actionsJsonDataProvider(): array
    {
        return [
            // #0, default behaviour, layout is not set
            [
                'from-config',
                function (string $result) {
                    $this->assertStringContainsString('Some title', $result);
                    $this->assertStringContainsString('Main From Config', $result);
                    $this->assertStringContainsString('<!-- index1 -->', $result);

                    $this->assertTrue(TestingPresenter::$actionPresenterFromConfigWasCalled);
                }
            ],
            // #1, default behaviour, layout is set, no name is defined for the other-view
            [
                'from-config2',
                function (string $result) {
                    $this->assertStringContainsString('Some title', $result);
                    $this->assertStringContainsString('Main From Config', $result);
                    $this->assertStringContainsString('<!-- index2 -->', $result);

                    $this->assertTrue(TestingPresenter::$actionPresenterFromConfigWasCalled);
                    $this->assertTrue(TestingView::$defaultViewWasRendered);
                }
            ]
        ];
    }

    /**
     * Running with actions
     *
     * @param string $page
     *            - loading page
     * @param callable $asserter
     *            asserting method
     * @dataProvider actionsJsonDataProvider
     */
    public function testActionsJson(string $page, callable $asserter): void
    {
        // setup
        $_GET['r'] = $page;
        $application = new TestCommonApplication();

        // test body
        ob_start();
        $application->run();
        $result = ob_get_flush();
        ob_clean();

        // assertions
        $asserter($result);
    }
}
