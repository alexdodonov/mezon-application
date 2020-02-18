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
    public function testConstructor()
    {
        $controller = new TestingController('Test');

        $this->assertEquals('Test', $controller->getControllerName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender()
    {
        $controller = new TestingController('Test');

        $this->assertEquals('computed content', $controller->run(), 'Invalid controller execution');
        $this->assertEquals('computed content 2', $controller->run('test2'), 'Invalid controller execution');
    }
}
