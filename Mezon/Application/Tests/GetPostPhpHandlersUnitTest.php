<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Application\Application;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetPostPhpHandlersUnitTest extends TestCase
{

    /**
     * Method constructs application with the default routes
     *
     * @return Application application object
     */
    protected function getTestApplicationWithTestRoutes(): Application
    {
        $application = new TestApplication();

        $application->loadRoutesFromConfig(__DIR__ . '/Conf2/TestRoutes.php');

        return $application;
    }

    /**
     * Data provider for the test testGetPostRoutesConfig
     *
     * @return array test data
     */
    public function getPostRoutesConfigDataProvider(): array
    {
        return [
            [
                'GET',
                '/get-route/'
            ],
            [
                'POST',
                '/post-route/'
            ]
        ];
    }

    /**
     * Testing GET & POST calls
     *
     * @dataProvider getPostRoutesConfigDataProvider
     */
    public function testGetPostRoutesConfig(string $method, string $route): void
    {
        // setup
        $_SERVER['REQUEST_METHOD'] = $method;
        $_GET['r'] = $route;

        $application = $this->getTestApplicationWithTestRoutes();

        // assertions
        $this->expectOutputString('OK!');

        // test body
        $application->run();
    }
}
