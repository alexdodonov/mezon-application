<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Controller;

class TestingController extends Controller
{

    public function controllerTest(): string
    {
        return 'computed content';
    }

    public function controllerTest2(): string
    {
        return 'computed content 2';
    }

    public $wasCalled = false;

    public function controllerResult(): void
    {
        $this->wasCalled = true;
    }
}
