<?php

/**
 * Controller class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingController extends \Mezon\Application\Controller
{

    public function controllerTest()
    {
        return 'computed content';
    }

    public function controllerTest2()
    {
        return 'computed content 2';
    }
}

class ControllerUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor():void
    {
        // setup
        $controller = new TestingController('Test');

        $this->assertEquals('Test', $controller->getControllerName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender():void
    {
        // setup
        $controller = new TestingController('Test');

        $this->assertEquals('computed content', $controller->run(), 'Invalid controller execution');
        $this->assertEquals('computed content 2', $controller->run('test2'), 'Invalid controller execution');
    }

    /**
     * Testing default controller
     */
    public function testDefault():void{
        // setup
        $controller = new TestingController();

        // assertions
        $this->expectExceptionMessage('Controller Default was not found');

        // test body
        $controller->run();
    }
    
}
