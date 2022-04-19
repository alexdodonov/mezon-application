<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadRoutesFromConfigsUnitTest extends TestCase
{

    /**
     * Testing loading configs from multyple files
     */
    public function testLoadRoutesFromConfigs(): void
    {
        // setup
        $application = new TestApplication();

        // test body
        $application->loadRoutesFromConfigs([
            __DIR__ . '/Conf2/TestRoutes.php',
            __DIR__ . '/Conf2/TestRoutes.json'
        ]);

        // assertions
        $this->assertTrue($application->routeExists('/test-php-route/'));
        $this->assertTrue($application->routeExists('/test-json-route/'));
    }
}
