<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadingFromUnexistingRouteUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        // TODO move to the root class
        // context setup
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * Trying to load unexisting config.
     */
    public function testLoadingFromUnexistingRoute(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('Route "unexisting" was not found');

        // setup
        $application = new TestApplication();

        // test body
        $application->loadRoutesFromConfig('unexisting');
    }
}
