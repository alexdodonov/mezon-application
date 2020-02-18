<?php

class TemplateResourcesUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing additing CSS file.
     */
    public function testAdditingSingleCSSFile()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()), 'CSS files array must be empty');

        $templateResources->addCssFile('./res/test.css');

        $this->assertEquals(1, count($templateResources->getCssFiles()), 'CSS files array must be NOT empty');

        $templateResources->clear();
    }

    /**
     * Testing additing CSS files.
     */
    public function testAdditingMultypleCSSFiles()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()), 'CSS files array must be empty');

        $templateResources->addCssFiles([
            './res/test.css',
            './res/test2.css'
        ]);

        $this->assertEquals(2, count($templateResources->getCssFiles()), 'CSS files array must be NOT empty');

        $templateResources->clear();
    }

    /**
     * Testing additing CSS files.
     */
    public function testDoublesCSSExcluding()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()), 'CSS files array must be empty');

        $templateResources->addCssFiles([
            './res/test.css',
            './res/test.css'
        ]);

        $this->assertEquals(1, count($templateResources->getCssFiles()), 'Only one path must be added');

        $templateResources->addCssFile('./res/test.css');

        $this->assertEquals(1, count($templateResources->getCssFiles()), 'Only one path must be added');

        $templateResources->clear();
    }

    /**
     * Testing additing JS file.
     */
    public function testAdditingSingleJSFile()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()), 'JS files array must be empty');

        $templateResources->addJsFile('./include/js/test.js');

        $this->assertEquals(1, count($templateResources->getJsFiles()), 'JS files array must be NOT empty');

        $templateResources->clear();
    }

    /**
     * Testing additing JS files.
     */
    public function testAdditingMultypleJSFiles()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()), 'JS files array must be empty');

        $templateResources->addJsFiles([
            './include/js/test.js',
            './include/js//test2.js'
        ]);

        $this->assertEquals(2, count($templateResources->getJsFiles()), 'JS files array must be NOT empty');

        $templateResources->clear();
    }

    /**
     * Testing additing JS files.
     */
    public function testDoublesJSExcluding()
    {
        $templateResources = new \Mezon\Application\TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()), 'JS files array must be empty');

        $templateResources->addJsFiles([
            './include/js/test.js',
            './include/js/test.js'
        ]);

        $this->assertEquals(1, count($templateResources->getJsFiles()), 'Only one path must be added');

        $templateResources->addJsFile('./include/js/test.js');

        $this->assertEquals(1, count($templateResources->getJsFiles()), 'Only one path must be added');

        $templateResources->clear();
    }
}
