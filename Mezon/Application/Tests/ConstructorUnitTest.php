<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use function Safe\ob_end_flush;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConstructorUnitTest extends TestCase
{

    /**
     * Testing that application's router is passed to the Request
     */
    public function testRouterToRequest(): void
    {
        // test body
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['r'] = 'param-route/112233';
        $application = new TestApplication();
        $application->loadRoutesFromConfig(__DIR__ . '/TestRoutes.json');

        // test body
        ob_start();
        $application->run();
        $content = ob_get_contents();
        ob_end_clean();

        // assertions
        $this->assertEquals('112233', $content);
    }
}
