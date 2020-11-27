<?php
namespace Mezon\Application\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;

class ViewUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setup
        $view = new TestingView(null, 'test');

        // test body and assertions
        $this->assertEquals('test', $view->getViewName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender(): void
    {
        // setup
        $view = new TestingView(null, 'test');

        // test body and assertions
        $this->assertEquals('rendered content', $view->render(), 'Invalid view renderring');
        $this->assertEquals('rendered content 2', $view->render('test2'), 'Invalid view renderring');
    }

    /**
     * Testing render
     */
    public function testUnexistingDefault(): void
    {
        // setup
        $view = new TestingViewUnexistingDefault();

        // assertions
        $this->expectExceptionMessage('View "Default" was not found');

        // test body
        $view->render();
    }

    /**
     * Testing template getter
     */
    public function testGetTemplate(): void
    {
        // setup
        $view = new TestingView(new HtmlTemplate(__DIR__ . '/Res/Templates/'));

        // test body and assertions
        $this->assertInstanceOf(HtmlTemplate::class, $view->getTemplate());
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

    /**
     * Testing method setErrorCode
     */
    public function testSetErrorCode(): void
    {
        // setup
        $view = new TestingView(new HtmlTemplate(__DIR__));

        // test body
        $view->setErrorCode(111);

        // assertions
        $this->assertEquals(111, $view->getErrorCode());
        $this->assertEquals(111, $view->getTemplate()
            ->getPageVar(TestingView::ERROR_CODE));
    }

    /**
     * Testing method setErrorMessage
     */
    public function testSetErrorMessage(): void
    {
        // setup
        $view = new TestingView(new HtmlTemplate(__DIR__));

        // test body
        $view->setErrorMessage('111');

        // assertions
        $this->assertEquals('111', $view->getErrorMessage());
        $this->assertEquals('111', $view->getTemplate()
            ->getPageVar(TestingView::ERROR_MESSAGE));
    }
}
