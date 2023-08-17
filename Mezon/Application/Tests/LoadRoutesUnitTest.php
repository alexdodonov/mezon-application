<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadRoutesUnitTest extends TestCase
{

    /**
     * Testing method loadRoutes
     */
    public function testLoadRoutes(): void
    {
        // setup
        $application = new Application();
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // test body
        $application->loadRoutes(
            [
                [
                    'route' => 'some-route',
                    'class' => TestApplication::class,
                    'callback' => 'compound'
                ]
            ]);

        /** @var array{0:TestApplication} $callback */
        $callback = $application->getRouter()->getCallback('some-route');

        // assertions
        $this->assertInstanceOf(TestApplication::class, $callback[0]);
    }
}
