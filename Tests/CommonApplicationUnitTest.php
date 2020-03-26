<?php

/**
 * View class
 *
 * @author Dodonov A.A.
 */
class TestView extends \Mezon\Application\View
{

    public function __construct(string $content)
    {
        parent::__construct('default');

        $this->Content = $content;
    }

    public function render(string $viewName = ''): string
    {
        return $this->Content;
    }
}

/**
 * Application for testing purposes.
 */
class TestCommonApplication extends \Mezon\Application\CommonApplication
{

    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct(new \Mezon\HtmlTemplate\HtmlTemplate(__DIR__, 'index'));
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
        throw (new \Mezon\Rest\Exception('exception', - 1, 502, 'body'));
    }
}

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

        // test body
        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        // assertions
        $this->assertStringContainsString('View result', $output, 'Template compilation failed (3)');
        $this->assertStringContainsString('Test view result', $output, 'Template compilation failed (4)');
    }

    /**
     * Testing handleException method
     */
    public function testHandleException()
    {
        // setup
        $application = new TestCommonApplication();
        $output = '';
        try {
            throw (new Exception('', 0));
        } catch (Exception $e) {
            // test body
            ob_start();
            $application->handleException($e);
            $output = ob_get_contents();
            ob_end_clean();
        }

        // assertions
        $this->assertStringContainsString('"message"', $output);
        $this->assertStringContainsString('"code"', $output);
        $this->assertStringContainsString('"call_stack"', $output);
        $this->assertStringContainsString('"host"', $output);
    }

    /**
     * Testing handle_rest_exception method
     */
    public function testHandleRestException()
    {
        // setup
        $application = new TestCommonApplication();
        $output = '';
        try {
            throw (new \Mezon\Rest\Exception('', 0, 200, ''));
        } catch (Exception $e) {
            // test body
            ob_start();
            $application->handleRestException($e);
            $output = ob_get_contents();
            ob_end_clean();
        }

        // assertions
        $this->assertStringContainsString('"message"', $output);
        $this->assertStringContainsString('"code"', $output);
        $this->assertStringContainsString('"call_stack"', $output);
        $this->assertStringContainsString('"host"', $output);
        $this->assertStringContainsString('"host"', $output);
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
            throw (new Exception('', 0));
        } catch (Exception $e) {
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
}
