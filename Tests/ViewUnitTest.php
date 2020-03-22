<?php

/**
 * View class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingView extends \Mezon\Application\View
{

    public function viewTest(): string
    {
        return 'rendered content';
    }

    public function viewTest2(): string
    {
        return 'rendered content 2';
    }
}

class ViewUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor():void
    {
        // setup
        $view = new TestingView('test');

        // test body and assertions
        $this->assertEquals('test', $view->getViewName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender():void
    {
        // setup
        $view = new TestingView('test');

        // test body and assertions
        $this->assertEquals('rendered content', $view->render(), 'Invalid view renderring');
        $this->assertEquals('rendered content 2', $view->render('test2'), 'Invalid view renderring');
    }
    
    /**
     * Testing render
     */
    public function testDefault():void
    {
        // setup
        $view = new TestingView();
        
        // assertions
        $this->expectExceptionMessage('View Default was not found');

        // test body
        $view->render();
    }
}
