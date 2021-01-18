<?php
namespace Mezon\Application\Tests;

use Mezon\Rest;
use Mezon\Application\View;
use PHPUnit\Framework\TestCase;

class ResultUnitTest extends TestCase
{

    /**
     * Data provider
     *
     * @return array data provider
     */
    public function resultMethodDataProvider(): array
    {
        $presenter = new TestingPresenter(new View(), 'Result');
        return [
            [ // #0 testing presenter
                function (): TestCommonApplication {
                    unset($_GET['action-message']);
                    return new TestCommonApplication();
                },
                $presenter,
                function (array $params) {
                    $this->assertTrue($params[0]->wasCalled);
                }
            ],
            [ // #1 testing action message setup
                function (): TestCommonApplication {
                    $_GET['action-message'] = 'test-error';
                    return new TestCommonApplication();
                },
                $presenter,
                function (array $params): void {
                    $this->assertEquals('error', $params[1]->getTemplate()
                        ->getPageVar('action-message'));
                }
            ],
            [ // #2 no file with the messages
                function (): TestCommonApplication {
                    $_GET['action-message'] = 'test-error';
                    $application = new TestCommonApplication();
                    $application->hasMessages = false;
                    return $application;
                },
                $presenter,
                function (array $params): void {
                    $this->assertEquals('', $params[1]->getTemplate()
                        ->getPageVar('action-message'));
                }
            ]
        ];
    }

    /**
     * Testing result() method
     *
     * @param callable $setup
     *            setup of the test
     * @param object $handler
     *            controller or presenter
     * @param callable $assert
     *            asserter
     * @dataProvider resultMethodDataProvider
     */
    public function testResultMethod(callable $setup, object $handler, callable $assert = null): void
    {
        // setup
        $application = $setup();

        // test body
        $application->result($handler);

        // assertions
        if ($assert !== null) {
            $assert([
                $handler,
                $application
            ]);
        }
    }
}
