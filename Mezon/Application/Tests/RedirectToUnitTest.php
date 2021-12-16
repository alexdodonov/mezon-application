<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;
use PHPUnit\Framework\TestCase;
use Mezon\Conf\Conf;
use Mezon\Redirect\Layer;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class RedirectToUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        Conf::setConfigStringValue('redirect/layer', 'mock');
    }

    /**
     * Testing method redirectTo
     */
    public function testRedirectTo(): void
    {
        // test body
        $_GET['redirect-to'] = 'redirect-url';
        $application = new Application();

        // test body
        $application->redirectTo('./some-url?redirect={redirect-to}');

        // assertions
        $this->assertTrue(Layer::$redirectWasPerformed);
        $this->assertEquals('./some-url?redirect=redirect-url', Layer::$lastRedirectionUrl);
    }
}
