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
    public function testConstructor()
    {
        $view = new TestingView('test');

        $this->assertEquals('test', $view->getViewName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender()
    {
        $view = new TestingView('test');

        $this->assertEquals('rendered content', $view->render(), 'Invalid view renderring');
        $this->assertEquals('rendered content 2', $view->render('test2'), 'Invalid view renderring');
    }
}
