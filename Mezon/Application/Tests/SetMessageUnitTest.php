<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Application\CommonApplication;
use Mezon\HtmlTemplate\HtmlTemplate;

class SetMessageUnitTest extends TestCase
{

    /**
     * Data provider for the test testSetErrorMessage
     *
     * @return array testing data
     */
    public function setErrorMessageDataProvider(): array
    {
        return [
            [
                'setSuccessMessage'
            ],
            [
                'setErrorMessage'
            ]
        ];
    }

    /**
     * Testing method
     *
     * @dataProvider setErrorMessageDataProvider
     */
    public function testSetErrorMessage(string $method): void
    {
        // setup
        $application = new CommonApplication(new HtmlTemplate([
            __DIR__ . '/Res/',
            __DIR__
        ]));

        // test body
        $application->$method('test-error');

        // assertions
        $this->assertStringContainsString('error', $application->getTemplate()
            ->getPageVar('action-message'));
    }
}
