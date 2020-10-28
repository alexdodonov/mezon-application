<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Presenter;

/**
 * Presenter class for testing purposes
 *
 * @author Dodonov A.A.
 */
class TestingPresenter extends Presenter
{

    public function presenterTest(): string
    {
        return 'computed content';
    }

    public function presenterTest2(): string
    {
        return 'computed content 2';
    }

    public $wasCalled = false;

    public function presenterResult(): void
    {
        $this->wasCalled = true;
    }
    
    public static $actionPresenterFromConfigWasCalled = false;
    
    public function presenterFromConfig(): void
    {
        TestingPresenter::$actionPresenterFromConfigWasCalled = true;
    }
}
