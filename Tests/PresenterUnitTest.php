<?php

/**
 * Presenter class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingPresenter extends \Mezon\Application\Presenter
{

    public function presenterTest(): string
    {
        return 'computed content';
    }

    public function presenterTest2(): string
    {
        return 'computed content 2';
    }
}

class PresenterUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function testConstructor(): void
    {
        // setupp
        $presenter = new TestingPresenter('Test');

        $this->assertEquals('Test', $presenter->getPresenterName(), 'Invalid constructor call');
    }

    /**
     * Testing render
     */
    public function testRender(): void
    {
        // setupp
        $presenter = new TestingPresenter('Test');

        $this->assertEquals('computed content', $presenter->run(), 'Invalid controller execution');
        $this->assertEquals('computed content 2', $presenter->run('test2'), 'Invalid controller execution');
    }

    /**
     * Testing default controller
     */
    public function testDefault(): void
    {
        // setupp
        $presenter = new TestingPresenter();

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
        $presenter = new TestingPresenter();

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
        $presenter = new TestingPresenter();

        // test body
        $presenter->setPresenterName('SomeName');

        // assertions
        $this->assertEquals('SomeName', $presenter->getPresenterName());
    }
}
