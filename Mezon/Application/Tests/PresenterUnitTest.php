<?php
namespace Mezon\Application\Tests;

use Mezon\Transport\HttpRequestParams;
use Mezon\Router\Router;
use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\ViewInterface;
use Mezon\Application\View;
use Mezon\Application\AbstractPresenter;

class PresenterUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setupp
        $presenter = new TestingPresenter(new TestingView(), 'Test');

        $this->assertEquals('Test', $presenter->getPresenterName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender(): void
    {
        // setupp
        $presenter = new TestingPresenter(new TestingView(), 'Test');

        $this->assertEquals('computed content', $presenter->run(), 'Invalid controller execution');
        $this->assertEquals('computed content 2', $presenter->run('test2'), 'Invalid controller execution');
    }

    /**
     * Testing default controller
     */
    public function testDefault(): void
    {
        // setupp
        $presenter = new TestingPresenter(new TestingView());

        // assertionss
        $this->expectExceptionMessage('Presenter Default was not found');

        // test bodyy
        $presenter->run();
    }

    /**
     * Method tests buildRoute
     */
    public function testBuildRoute(): void
    {
        // setup
        $presenter = new TestingPresenter(new TestingView());

        // test body
        $route = $presenter->buildRoute('/test/', 'POST', 'controllerTest');

        // assertions
        $this->assertEquals('/test/', $route['route']);
        $this->assertEquals('POST', $route['method']);
        $this->assertInstanceOf(TestingPresenter::class, $route['callback'][0]);
        $this->assertEquals('controllerTest', $route['callback'][1]);
    }

    /**
     * Testing method setPresenterName
     */
    public function testSetPresenterName(): void
    {
        // setup
        $presenter = new TestingPresenter(new TestingView());

        // test body
        $presenter->setPresenterName('SomeName');

        // assertions
        $this->assertEquals('SomeName', $presenter->getPresenterName());
    }

    /**
     * Testing method getRequestParamsFetcher
     */
    public function testGetParamsFetcher(): void
    {
        // setup
        $router = new Router();
        $presenter = new TestingPresenter(new TestingView(), '', new HttpRequestParams($router));

        // test body
        $fetcher = $presenter->getRequestParamsFetcher();

        // assertions
        $this->assertInstanceOf(HttpRequestParams::class, $fetcher);
    }

    /**
     * Testing method getRequestParamsFetcher with exception
     */
    public function testGetParamsFetcherWithException(): void
    {
        // setup
        $presenter = new TestingPresenter(new TestingView());

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $presenter->getRequestParamsFetcher();
    }

    /**
     * Testing get/set method
     */
    public function testNullViewInPresenterConstructor(): void
    {
        // setup
        $presenter = new TestingPresenter(null);

        // test body
        $presenter->setErrorCode(11);
        $presenter->setErrorMessage('msg1');

        // assertions
        $this->assertEquals(11, $presenter->getErrorCode());
        $this->assertEquals('msg1', $presenter->getErrorMessage());
    }

    public function getSetMessagesDataProvider(): array
    {
        return [
            [
                new TestingPresenter()
            ],
            [
                new TestingPresenter(new TestingView(new HtmlTemplate(__DIR__, 'index')))
            ]
        ];
    }

    /**
     * Testing get/set method
     *
     * @dataProvider getSetMessagesDataProvider
     */
    public function testGetSetMessages(AbstractPresenter $presenter): void
    {
        // test body
        $presenter->setErrorCode(12);
        $presenter->setErrorMessage('msg2');
        $presenter->setSuccessMessage('msg4');

        // assertions
        $this->assertEquals(12, $presenter->getErrorCode());
        $this->assertEquals('msg2', $presenter->getErrorMessage());
        $this->assertEquals('msg4', $presenter->getSuccessMessage());
    }

    /**
     * Data provider for the test testSetViewParameter
     *
     * @return array
     */
    public function setViewParemeterDataProvider(): array
    {
        return [
            [
                new TestingView(new HtmlTemplate(__DIR__, 'index')),
                'val'
            ],
            [
                null,
                null
            ]
        ];
    }

    /**
     * Testing method setViewParameter
     *
     * @param
     *            ?ViewInterface View
     * @param mixed $var
     *            expected variable name
     * @dataProvider setViewParemeterDataProvider
     */
    public function testSetViewParameter(?ViewInterface $view, $var): void
    {
        // setup
        $presenter = new TestingPresenter($view);

        // test body
        $presenter->setViewParameter('var', 'val', true);

        // assertions
        $this->assertEquals($var, $presenter->getViewParameter('var'));
    }
}
