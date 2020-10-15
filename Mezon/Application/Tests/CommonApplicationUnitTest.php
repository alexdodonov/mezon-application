<?php
namespace Mezon\Application\Tests;

use Mezon\Rest;
use Mezon\Application\View;

class CommonApplicationUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Running with complex router result
     */
    public function testComplexRouteResult()
    {
        $application = new TestCommonApplication();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['r'] = '/array-result/';

        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertTrue(strpos($output, 'Array result') !== false, 'Template compilation failed (1)');
        $this->assertTrue(strpos($output, 'Route main') !== false, 'Template compilation failed (2)');
    }

    /**
     * Compiling page with functional view
     */
    public function testComplexViewRenderring()
    {
        // setup
        $application = new TestCommonApplication();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['r'] = '/view-result/';
        $_GET['redirect-to'] = 'redirectTo';

        // test body
        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        // assertions
        $this->assertStringContainsString('Page title', $output);
        $this->assertStringContainsString('View rendered content', $output);
        $this->assertStringContainsString('redirectTo', $output);
    }

    /**
     * Method asserts exception field
     *
     * @param string $output
     *            textual representation of the exception
     */
    protected function assertExceptionFields(string $output): void
    {
        $this->assertStringContainsString('"message"', $output);
        $this->assertStringContainsString('"code"', $output);
        $this->assertStringContainsString('"call_stack"', $output);
        $this->assertStringContainsString('"host"', $output);
    }

    /**
     * Testing handleException method
     */
    public function testHandleException()
    {
        // setup
        $application = new TestCommonApplication();
        $output = '';
        $e = new \Exception('', 0);

        // test body
        ob_start();
        $application->handleException($e);
        $output = ob_get_contents();
        ob_end_clean();

        // assertions
        $this->assertExceptionFields($output);
    }

    /**
     * Testing handle_rest_exception method
     */
    public function testHandleRestException()
    {
        // setup
        $application = new TestCommonApplication();

        $e = new Rest\Exception('', 0, 200, '');
        // test body
        ob_start();
        $application->handleRestException($e);
        $output = ob_get_contents();
        ob_end_clean();

        // assertions
        $this->assertExceptionFields($output);
        $this->assertStringContainsString('"httpBody"', $output);
    }

    /**
     * Testing handleException method
     */
    public function testHandleExceptionWithHost()
    {
        // setup
        $application = new TestCommonApplication();
        $output = '';
        $_SERVER['HTTP_HOST'] = 'some host';
        $_SERVER['REQUEST_URI'] = 'some uri';
        try {
            throw (new \Exception('', 0));
        } catch (\Exception $e) {
            // test body
            ob_start();
            $application->handleException($e);
            $output = ob_get_contents();
            ob_end_clean();
        }

        // assertions
        $output = json_decode(str_replace('<pre>', '', $output), true);
        $this->assertEquals('some hostsome uri', $output['host']);
    }

    /**
     * Testing exception throwing after invalid route handling
     */
    public function testInvalidRouteException(): void
    {
        // setup and assertions
        $_GET['r'] = 'invalid';
        $application = $this->getMockBuilder(TestCommonApplication::class)
            ->setMethods([
            'handleException'
        ])
            ->getMock();
        $application->expects($this->once())
            ->method('handleException');

        // test body
        $application->run();
    }

    /**
     * Testing exception throwing after invalid route handling
     */
    public function testRestException(): void
    {
        // setup and assertions
        $_GET['r'] = 'rest';
        $application = $this->getMockBuilder(TestCommonApplication::class)
            ->setMethods([
            'handleRestException'
        ])
            ->getMock();
        $application->expects($this->once())
            ->method('handleRestException');

        // test body
        $application->run();
    }

    /**
     * Data provider
     *
     * @return array data provider
     */
    public function resultMethodDataProvider(): array
    {
        $presenter = new TestingPresenter(new View(), 'Result');
        return [
            [ // #0 testing controller
                function (): TestCommonApplication {
                    return new TestCommonApplication();
                },
                new TestingController('Result'),
                function (array $params) {
                    $this->assertTrue($params[0]->wasCalled);
                }
            ],
            [ // #1 testing presenter
                function (): TestCommonApplication {
                    return new TestCommonApplication();
                },
                $presenter,
                function (array $params) {
                    $this->assertTrue($params[0]->wasCalled);
                }
            ],
            [ // #2 testing action message setup
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
            [ // #3 no file with the messages
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

    /**
     * Testing exception wile action-message parsing
     */
    public function testUnexistingException(): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $_GET['action-message'] = 'unexisting-message';
        $application = new TestCommonApplication();

        // test body
        $application->result();
    }
}
