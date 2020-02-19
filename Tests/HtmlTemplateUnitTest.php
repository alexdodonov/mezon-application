<?php

class HtmlTemplateUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing construction with default path
     */
    public function testConstructor1()
    {
        // setup and test body
        $template = new \Mezon\Application\HtmlTemplate(__DIR__ . '/test-data/', 'index', [
            'main'
        ]);

        $content = $template->compile();

        // assertions
        $this->assertStringContainsString('<body>', $content, 'Layout was not setup');
        $this->assertStringContainsString('<section>', $content, 'Block was not setup');
    }

    /**
     * Testing construction with flexible path
     */
    public function testConstructor2()
    {
        // setup and test body
        $template = new \Mezon\Application\HtmlTemplate(__DIR__ . '/test-data/res/', 'index2', [
            'main'
        ]);

        $content = $template->compile();

        // assertions
        $this->assertStringContainsString('<body>', $content, 'Layout was not setup');
        $this->assertStringContainsString('<section>', $content, 'Block was not setup');
    }

    /**
     * Testing invalid construction
     */
    public function testInvalidConstructor()
    {
        $this->expectException(Exception::class);

        // setup and test body
        $template = new \Mezon\Application\HtmlTemplate(__DIR__, 'index3', [
            'main'
        ]);

        // debug if the exception was not thrown
        var_dump($template);
    }

    /**
     * Testing that all unused place holders will be removed
     */
    public function testCompile()
    {
        // setup
        $template = new \Mezon\Application\HtmlTemplate(__DIR__ . '/test-data/res/', 'index2', [
            'main'
        ]);
        $_SERVER['HTTP_HOST'] = 'host';

        // test body
        $result = $template->compile();

        // assertions
        $this->assertStringNotContainsStringIgnoringCase('{title}', $result);
    }

    /**
     * Testing unexisting block
     */
    public function testGetUnexistingBlock()
    {
        // setup and test body
        $template = new \Mezon\Application\HtmlTemplate(__DIR__ . '/test-data/', 'index', [
            'main'
        ]);

        $this->expectException(Exception::class);

        // test body
        $template->getBlock('unexisting');
    }

    /**
     * Test existing var fetch
     */
    public function testGetExistingVar(): void
    {
        // setup
        $template = new \Mezon\Application\HtmlTemplate(__DIR__);
        $template->setPageVar('existing-var', 'existing value');

        // test body and assertions
        $this->assertEquals('existing value', $template->getPageVar('existing-var'));
    }

    /**
     * Test unexisting var fetch
     */
    public function testGetUnExistingVar(): void
    {
        // setup
        $template = new \Mezon\Application\HtmlTemplate(__DIR__);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $template->getPageVar('unexisting-var');
    }
}
