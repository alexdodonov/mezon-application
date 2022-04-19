<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadRoutesFromDirectoryUnitTest extends TestCase
{

    /**
     * Testing loadRoutesFromDirectory method
     */
    public function testLoadRoutesFromDirectory(): void
    {
        // setup
        $application = new TestApplication();

        // test body
        $application->loadRoutesFromDirectory(__DIR__ . '/Conf2/');

        // assertions
        $this->assertTrue($application->routeExists('/test-php-route/'));
        $this->assertTrue($application->routeExists('/test-json-route/'));
    }
}
