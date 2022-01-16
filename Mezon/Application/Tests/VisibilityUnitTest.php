<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use PHPUnit\Framework\TestCase;


/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class VisibilityUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function protectedMethodsVisibilityDataProvider(): array
    {
        return [
            // #0
            [
                'callRoute'
            ],
            // #1
            [
                'formatCallStack'
            ],
            // #2
            [
                'getClassPath'
            ]
        ];
    }

    /**
     * Testing protected methods visibility
     *
     * @param string $method
     *            method to be checked
     * @dataProvider protectedMethodsVisibilityDataProvider
     */
    public function testProtectedMethodsVisibility(string $method): void
    {
        // TODO for testing purposes make class for testing protected methods visibility
        // Like this : class VisibilityUnitTest extends VisibilityUnitTests{
        // public $methods = ['method1', 'method2', '...'];
        // }
        // setup
        $method = new \ReflectionMethod(Application::class, $method);

        // test body and assertions
        $this->assertTrue($method->isProtected());
    }
}
