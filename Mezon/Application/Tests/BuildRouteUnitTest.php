<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class BuildRouteUnitTest extends TestCase
{

    /**
     * Testing method buildRoute
     */
    public function testBuildRoute(): void
    {
        // test body
        $result = Application::buildRoute('/route/', 'GET', $this, 'func');

        // assertions
        $this->assertEquals('/route/', $result['route']);
        $this->assertEquals('GET', $result['method']);
        $this->assertInstanceOf(BuildRouteUnitTest::class, $result['callback'][0]);
        $this->assertEquals('func', $result['callback'][1]);
    }
}
