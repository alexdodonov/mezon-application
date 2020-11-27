<?php
namespace Mezon\Application\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\Application\ViewStatic;
use PHPUnit\Framework\TestCase;

class ViewStaticUnitTest extends TestCase
{

    /**
     * Testing constructor
     */
    public function testRender(): void
    {
        // setup
        $template = new HtmlTemplate(__DIR__ . '/Res/');
        $template->setPageVar('block', 'BLOCK!!!');
        $view = new ViewStatic($template, 'block');

        // test body
        $content = $view->render();

        // assertions
        $this->assertEquals('some BLOCK!!! {unexisting-var}', $content);
        $this->assertEquals('block', $view->getViewName());
    }

    /**
     * Testing unexisting block renderring
     */
    public function testEmptyBlockName(): void
    {
        // setup
        $this->expectException(\Exception::class);
        $template = new HtmlTemplate(__DIR__ . '/Res/');
        $view = new ViewStatic($template);

        // test body and assertions
        $view->render();
    }
}
