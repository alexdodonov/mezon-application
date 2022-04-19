<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ApplicationUnitTest extends TestCase
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
        $application = new TestApplication();

        $_GET['r'] = '/unexisting/';

        ob_start();
        $application->run();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertTrue(
            strpos($output, 'The processor was not found for the route') !== false,
            'Invalid behavior with incorrect route');
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
}
