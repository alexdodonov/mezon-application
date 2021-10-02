<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;

class ApplicationUnitTest extends \PHPUnit\Framework\TestCase
{
    
    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        // context setup
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

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

        $this->expectException(\Exception::class);
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
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        $this->assertEquals('Field "callback" must be set', $msg, 'Invalid behavior for callback');
    }

    /**
     * Method constructs application with the default routes
     *
     * @return \Mezon\Application\Application
     */
    protected function getTestApplicationWithTestRoutes(): \Mezon\Application\Application
    {
        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.php');

        return $application;
    }

    /**
     * Testing loading routes from config file.
     */
    public function testRoutesPhpConfig(): void
    {
        // setup
        $application = $this->getTestApplicationWithTestRoutes();

        $_GET['r'] = '/get-route/';

        // assertions
        $this->expectOutputString('OK!');

        // test body
        $application->run();
    }

    /**
     * Testing loading routes from config file.
     */
    public function testRoutesJsonConfig(): void
    {
        // setup
        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.json');

        $_GET['r'] = '/get-route/';

        // assertions
        $this->expectOutputString('OK!');

        // test body
        $application->run();
    }

    /**
     * Testing loading POST routes from config file.
     */
    public function testPostRoutesConfig(): void
    {
        // setup
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $application = $this->getTestApplicationWithTestRoutes();

        $_GET['r'] = '/post-route/';

        // assertions
        $this->expectOutputString('OK!');

        // test body
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
        } catch (\Exception $e) {
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
        $this->expectException(\Exception::class);

        $application = $this->getMock();
        $application->method('handleException')->willThrowException(new \Exception());

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
        $application->loadRoutesFromDirectory(__DIR__ . '/Conf/');

        // assertions
        $this->assertTrue($application->routeExists('/php-route/'));
        $this->assertTrue($application->routeExists('/json-route/'));
    }

    /**
     * Testing method getRequestParamsFetcher
     */
    public function testGetRequestParamsFetcher(): void
    {
        // setup
        $application = new \Mezon\Application\Application();

        // test body
        $requestParams = $application->getRequestParamsFetcher();

        // assertions
        $this->assertInstanceOf(\Mezon\Transport\HttpRequestParams::class, $requestParams);
    }

    /**
     * Testing method
     */
    public function testBuildRoute(): void
    {
        // test body
        $result = Application::buildRoute('/route/', 'GET', $this, 'func');

        // assertions
        $this->assertEquals('/route/', $result['route']);
        $this->assertEquals('GET', $result['method']);
        $this->assertInstanceOf(ApplicationUnitTest::class, $result['callback'][0]);
        $this->assertEquals('func', $result['callback'][1]);
    }
}
