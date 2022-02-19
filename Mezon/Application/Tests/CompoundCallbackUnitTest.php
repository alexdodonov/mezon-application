<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CompoundCallbackUnitTest extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        // context setup
        // TODO make base class
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * Testing compound routes ['callback'=>[object, function-name]]
     */
    public function testCompondCallback(): void
    {
        // setup and test body
        $_GET['r'] = 'compound';
        $application = new TestApplication();
        $application->loadRoute([
            'callback' => [
                $application,
                'compound'
            ],
            'route' => '/compound/'
        ]);

        // assertions
        ob_start();
        $application->run();
        $content = ob_get_flush();
        ob_clean();

        $this->assertEquals('compound', $content);
    }
}
