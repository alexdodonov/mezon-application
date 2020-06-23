<?php
use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\Presenter;
use Mezon\Application\View;

/**
 * View class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingView extends View
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

class ViewStaticUnitTest extends \PHPUnit\Framework\TestCase
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
    public function testDefault(): void
    {
        // setup
        $view = new TestingView();

        // assertions
        $this->expectExceptionMessage('View Default was not found');

        // test body
        $view->render();
    }

    /**
     * Testing template getter
     */
    public function testGetTemplate(): void
    {
        // setup
        $view = new TestingView(new HtmlTemplate(__DIR__ . '/res/templates/'));

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
     * Testing method getPresenter
     */
    public function testExceptionInPresenterFetcher(): void
    {
        // setup
        $view = new View(null, '', null);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $view->getPresenter();
    }

    /**
     * Testing method getPresenter
     */
    public function testNoExceptionInPresenterFetcher(): void
    {
        // setup
        $view = new View(null, '', new Presenter());

        // test body
        $presenter = $view->getPresenter();

        // assertions
        $this->assertInstanceOf(Presenter::class, $presenter);
    }
}
