<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use Mezon\Transport\HttpRequestParams;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetRequestParamsFetcherUnitTest extends TestCase
{

    /**
     * Testing method getRequestParamsFetcher
     */
    public function testGetRequestParamsFetcher(): void
    {
        // setup
        $application = new Application();

        // test body
        $requestParams = $application->getRequestParamsFetcher();

        // assertions
        $this->assertInstanceOf(HttpRequestParams::class, $requestParams);
    }
}
