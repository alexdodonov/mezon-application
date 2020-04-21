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

    function compound()
    {
        return 'compond';
    }
}

/**
 * Application for testing purposes.
 */
class TestApplication2 extends \Mezon\Application\Application
{

    var $counter = 0;

    /**
     * Method loads routes from config file in *.php or *.json format
     *
     * @param string $configPath
     *            Path of the config for routes
     */
    public function loadRoutesFromConfig(
        string $configPath = \Mezon\Application\CommonApplication::DEFAULT_PHP_ROUTES_PATH): void
    {
        $this->counter ++;
    }
}

class ApplicationUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Running with correct router.
     */
    public function testCorrectRoute(): void
    {
        $application = new TestApplication();

        $_GET['r'] = '/existing/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Running with incorrect router.
     */
    public function testIncorrectRoute(): void
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
    public function testConfigValidatorsRoute(): void
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
    public function testConfigValidatorsCallback(): void
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
    public function testRoutesPhpConfig(): void
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
    public function testRoutesJsonConfig(): void
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
    public function testPostRoutesConfig(): void
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
    public function testLoadingFromUnexistingRoute(): void
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
    protected function getMock(): object
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
    public function testUnexistingRouter(): void
    {
        $this->expectException(Exception::class);

        $application = $this->getMock();
        $application->method('handleException')->willThrowException(new Exception());

        $application->run();
    }

    /**
     * Testing call of the method added onthe fly.
     */
    public function testOnTheFlyMethod(): void
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
     * Testing call of the method added in runtime
     */
    public function testOnTheFlyUnexistingMethod(): void
    {
        // setup
        $application = new \Mezon\Application\Application();

        $application->fly = function () {
            return 'OK!';
        };

        // test body
        $application->fly();

        // assertions
        $this->addToAssertionCount(1);
    }

    /**
     * Testing unexisting method call
     */
    public function testUnexistingMethodCall(): void
    {
        // setup
        $application = new \Mezon\Application\Application();

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $application->unexistingMethod();
    }

    /**
     * Testing loading configs from multyple files
     */
    public function testLoadRoutesFromConfigs(): void
    {
        // setup
        $application = new TestApplication();

        // test body
        $application->loadRoutesFromConfigs([
            __DIR__ . '/TestRoutes.php',
            __DIR__ . '/TestRoutes.json'
        ]);

        // assertions
        $this->assertTrue($application->routeExists('/php-route/'));
        $this->assertTrue($application->routeExists('/json-route/'));
    }

    /**
     * Testing method
     */
    public function testLoadingDefaultConfigs(): void
    {
        // setup and test body
        $application = new TestApplication2();

        // assertions
        $this->assertEquals(2, $application->counter);
    }

    /**
     * Testing compound routes ['callback'=>[object, function-name]]
     */
    public function testCompondCallback(): void
    {
        // setup and test body
        $application = new TestApplication();
        $application->loadRoute([
            'callback' => [
                $this,
                'compound'
            ],
            'route' => '/compound/'
        ]);

        // assertions
        $this->assertTrue($application->routeExists('/compound/'));
    }

    /**
     * Testing loadRoutesFromDirectory method
     */
    public function testLoadRoutesFromDirectory(): void
    {
        // setup
        $application = new TestApplication();

        // test body
        $application->loadRoutesFromDirectory(__DIR__.'/../conf');

        // assertions
        $this->assertTrue($application->routeExists('/php-route/'));
        $this->assertTrue($application->routeExists('/json-route/'));
    }
}
