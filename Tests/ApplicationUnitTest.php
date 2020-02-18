<?php

/**
 * Application for testing purposes.
 */
class TestApplication extends \Mezon\Application\Application
{

    function __construct()
    {
        if (is_object($this->router)) {
            $this->router->clear();
        }

        parent::__construct();
    }

    function actionExisting()
    {
        /* existing action */
        return 'OK!';
    }

    function drop_router()
    {
        $this->router = false;
    }
}

class ApplicationUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Running with correct router.
     */
    public function testCorrectRoute()
    {
        $application = new TestApplication();

        $_GET['r'] = '/existing/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Running with incorrect router.
     */
    public function testIncorrectRoute()
    {
        $application = new TestApplication();

        $_GET['r'] = '/unexisting/';

        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertTrue(
            strpos($output, 'The processor was not found for the route') !== false,
            'Invalid behavior with incorrect route');
    }

    /**
     * Test config structure validators.
     */
    public function testConfigValidatorsRoute()
    {
        $application = new TestApplication();

        $msg = '';

        $this->expectException(Exception::class);
        $application->loadRoutesFromConfig(__DIR__ . '/TestInvalidRoutes1.php');

        $this->assertEquals('Field "route" must be set', $msg, 'Invalid behavior for config validation');
    }

    /**
     * Test config structure validators.
     */
    public function testConfigValidatorsCallback()
    {
        $application = new TestApplication();

        $msg = '';

        try {
            $application->loadRoutesFromConfig(__DIR__ . '/TestInvalidRoutes2.php');
        } catch (Exception $e) {
            $msg = $e->getMessage();
        }

        $this->assertEquals('Field "callback" must be set', $msg, 'Invalid behavior for callback');
    }

    /**
     * Testing loading routes from config file.
     */
    public function testRoutesPhpConfig()
    {
        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.php');

        $_GET['r'] = '/get-route/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Testing loading routes from config file.
     */
    public function testRoutesJsonConfig()
    {
        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.json');

        $_GET['r'] = '/get-route/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Testing loading POST routes from config file.
     */
    public function testPostRoutesConfig()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.php');

        $_GET['r'] = '/post-route/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Trying to load unexisting config.
     */
    public function testLoadingFromUnexistingRoute()
    {
        try {
            $application = new TestApplication();

            $application->loadRoutesFromConfig('unexisting');

            $this->assertEquals(true, false, 'Exception was not thrown');
        } catch (Exception $e) {
            $this->assertEquals(true, true, 'OK');
        }
    }

    /**
     * Method returns mocko bject of the application.
     */
    protected function getMock()
    {
        return $this->getMockBuilder(\Mezon\Application\Application::class)
            ->disableOriginalConstructor()
            ->setMethods([
            'handleException'
        ])
            ->getMock();
    }

    /**
     * Trying to load unexisting config.
     */
    public function testUnexistingRouter()
    {
        $this->expectException(Exception::class);

        $application = $this->getMock();
        $application->method('handleException')->willThrowException(new Exception());

        $application->run();
    }

    /**
     * Testing call of the method added onthe fly.
     */
    public function testOnTheFlyMethod()
    {
        $application = new \Mezon\Application\Application();

        $application->fly = function () {
            return 'OK!';
        };

        $application->loadRoute([
            'route' => '/fly-route/',
            'callback' => 'fly'
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['r'] = '/fly-route/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Testing call of the method added onthe fly.
     */
    public function testOnTheFlyUnexistingMethod()
    {
        $application = new \Mezon\Application\Application();

        $application->unexisting = function () {
            return 'OK!';
        };

        $application->fly();

        $this->addToAssertionCount(1);
    }
}
