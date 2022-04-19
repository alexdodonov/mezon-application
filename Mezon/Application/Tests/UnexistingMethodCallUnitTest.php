<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class UnexistingMethodCallUnitTest extends TestCase
{

    /**
     * Testing unexisting method call
     */
    public function testUnexistingMethodCall(): void
    {
        // setup
        $application = new Application();

        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage(
            'Method unexistingMethod was not found in the application Mezon\Application\Application');

        // test body
        $application->unexistingMethod();
    }
}
