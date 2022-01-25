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

        // test body
        $application->loadRoutes(
            [
                [
                    'route' => 'some-route',
                    'class' => TestApplication::class,
                    'callback' => 'compound'
                ]
            ]);

        // assertions
        $this->assertEquals(
            'GET : some-route, ; POST : <none>; PUT : <none>; DELETE : <none>; OPTION : <none>; PATCH : <none>',
            $application->getRouter()
                ->getAllRoutesTrace());
    }
}