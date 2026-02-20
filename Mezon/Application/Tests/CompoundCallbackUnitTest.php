<?php
namespace Mezon\Application\Tests;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CompoundCallbackUnitTest extends ApplicationTests
{

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
