<?php

/**
 * Controller class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingController extends \Mezon\Application\Controller
{

    public function controllerTest(): string
    {
        return 'computed content';
    }

    public function controllerTest2(): string
    {
        return 'computed content 2';
    }
}

class ControllerUnitTest extends \PHPUnit\Framework\TestCase
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
}
