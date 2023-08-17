<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Application\Files;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilesUnitTest extends TestCase
{

    /**
     * Testing method
     */
    public function testGetRootPath(): void
    {
        // setup
        Files::$rootPath = '';

        // test body and assertions
        $this->assertEquals(dirname(__DIR__, 5), Files::getRootPath());
    }

    /**
     * Testing method Files::getFile
     */
    public function testGetFile(): void
    {
        // setup
        Files::$rootPath = __DIR__;

        // test body and assertions
        $this->assertEquals('content', Files::getFile('/Data/Data.txt'));
    }
}
