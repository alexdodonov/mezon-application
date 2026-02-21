<?php
namespace Mezon\Application\Tests;

use Mezon\Application\Application;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ApplicationUnitTest extends ApplicationTests
{

    /**
     * Method runs application
     *
     * @param Application $application
     *            Application object
     * @return string application execution result
     */
    protected function runApplication(Application $application): string
    {
        // TODO этот метод в других файлах с тестами нужен? Если да, то в трейт
        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Running with correct router.
     */
    public function testCorrectRoute(): void
    {
        $application = new TestApplication();

        $_GET['r'] = '/existing/';

        $this->expectOutputString('OK!');

        $application->run();
    }

    /**
     * Running with incorrect router.
     */
    public function testIncorrectRoute(): void
    {
        $_GET['r'] = '/unexisting/';

        $output = $this->runApplication(new TestApplication());

        $this->assertTrue(strpos($output, 'The processor was not found for the route') !== false, 'Invalid behavior with incorrect route');
    }

    /**
     * Test config structure validators.
     */
    public function testConfigValidatorsRoute(): void
    {
        $application = new TestApplication();

        $msg = '';

        $this->expectException(\Exception::class);
        $application->loadRoutesFromConfig(__DIR__ . '/TestInvalidRoutes1.php');

        $this->assertEquals('Field "route" must be set', $msg, 'Invalid behavior for config validation');
    }

    /**
     * Test config structure validators.
     */
    public function testConfigValidatorsCallback(): void
    {
        $application = new TestApplication();

        $msg = '';

        try {
            $application->loadRoutesFromConfig(__DIR__ . '/TestInvalidRoutes2.php');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        $this->assertEquals('Field "callback" must be set', $msg, 'Invalid behavior for callback');
    }

    /**
     * Testing 'protected' access to the method Application::callRoute()
     */
    public function testProtectedCallRoute(): void
    {
        // setup
        $application = new class() extends Application {

            public function run(): void
            {
                try {
                    print($this->callRoute());
                } catch (\Exception $e) {
                    $this->handleException($e);
                }
            }
        };

        // test body
        $output = $this->runApplication($application);

        // assertions
        $this->assertStringContainsString('The processor was not found for the route unexisting', $output);
    }
}
