<?php
namespace Mezon\Application\Tests;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LoadingFromUnexistingRouteUnitTest extends ApplicationTests
{

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
