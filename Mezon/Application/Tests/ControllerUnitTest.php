<?php
namespace Mezon\Application\Tests;

use Mezon\Transport\HttpRequestParams;
use Mezon\Router\Router;
use PHPUnit\Framework\TestCase;

class ControllerUnitTest extends TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setupp
        $controller = new TestingController('Test');

        $this->assertEquals('Test', $controller->getControllerName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender(): void
    {
        // setupp
        $controller = new TestingController('Test');

        $this->assertEquals('computed content', $controller->run(), 'Invalid controller execution');
        $this->assertEquals('computed content 2', $controller->run('test2'), 'Invalid controller execution');
    }

    /**
     * Testing default controller
     */
    public function testDefault(): void
    {
        // setupp
        $controller = new TestingController();

        // assertionss
        $this->expectExceptionMessage('Controller Default was not found');

        // test bodyy
        $controller->run();
    }

    /**
     * Method tests buildRoute
     */
    public function testBuildRoute(): void
    {
        // setup
        $controller = new TestingController();

        // test body
        $route = $controller->buildRoute('/test/', 'POST', 'controllerTest');

        // assertions
        $this->assertEquals('/test/', $route['route']);
        $this->assertEquals('POST', $route['method']);
        $this->assertInstanceOf(TestingController::class, $route['callback'][0]);
        $this->assertEquals('controllerTest', $route['callback'][1]);
    }

    /**
     * Testing method setControllerName
     */
    public function testSetControllerName(): void
    {
        // setup
        $controller = new TestingController();

        // test body
        $controller->setControllerName('SomeName');

        // assertions
        $this->assertEquals('SomeName', $controller->getControllerName());
    }

    /**
     * Testing method getRequestParamsFetcher
     */
    public function testGetParamsFetcher(): void
    {
        // setup
        $router = new Router();
        $controller = new TestingController('', new HttpRequestParams($router));

        // test body
        $fetcher = $controller->getRequestParamsFetcher();

        // assertions
        $this->assertInstanceOf(HttpRequestParams::class, $fetcher);
    }

    /**
     * Testing method getRequestParamsFetcher with exception
     */
    public function testGetParamsFetcherWithException(): void
    {
        // setup
        $controller = new TestingController();

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $controller->getRequestParamsFetcher();
    }
}
