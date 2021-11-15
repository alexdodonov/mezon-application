<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use Mezon\Transport\HttpRequestParams;
use PHPUnit\Framework\TestCase;

class OnTheFlyMethodUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        // context setup
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * Testing call of the method added onthe fly.
     */
    public function testOnTheFlyMethod(): void
    {
        /** @var object{fly: callable} $application */
        $application = new Application();

        $application->fly = function (): string {
            return 'OK!';
        };

        $application->loadRoute([
            'route' => '/fly-route/',
            'callback' => 'fly'
        ]);

        $_GET['r'] = '/fly-route/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Testing call of the method added in runtime
     */
    public function testOnTheFlyUnexistingMethod(): void
    {
        // setup
        /** @var object{fly: callable} $application */
        $application = new Application();

        $application->fly = function (): string {
            return 'OK!';
        };

        // test body
        $result = $application->fly();

        // assertions
        $this->assertEquals('OK!', $result);
    }
}
