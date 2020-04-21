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
    public function testConstructor(): void
    {
        // setupp
        $view = new TestingView(null, 'test');

        // test body and assertionss
        $this->assertEquals('test', $view->getViewName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender(): void
    {
        // setupp
        $view = new TestingView(null, 'test');

        // test body and assertionss
        $this->assertEquals('rendered content', $view->render(), 'Invalid view renderring');
        $this->assertEquals('rendered content 2', $view->render('test2'), 'Invalid view renderring');
    }

    /**
     * Testing render
     */
    public function testDefault(): void
    {
        // setupp
        $view = new TestingView();

        // assertionss
        $this->expectExceptionMessage('View Default was not found');

        // test bodyy
        $view->render();
    }

    /**
     * Testing template getter
     */
    public function testGetTemplate(): void
    {
        // setupp
        $view = new TestingView(new \Mezon\HtmlTemplate\HtmlTemplate(__DIR__ . '/res/templates/'));

        // test body and assertions
        $this->assertInstanceOf(\Mezon\HtmlTemplate\HtmlTemplate::class, $view->getTemplate());
    }

    /**
     * Testing template getter with exception
     */
    public function testGetTemplateException(): void
    {
        // setup and assertions
        $view = new TestingView();
        $this->expectException(\Exception::class);

        // test body
        $view->getTemplate();
    }
}
