<?php
namespace Mezon\Application;

/**
 * Class HtmlTemplate
 *
 * @package Mezon
 * @subpackage HtmlTemplate
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Template class
 *
 * @author Dodonov A.A.
 */
class HtmlTemplate
{

    /**
     * Loaded template content
     */
    private $template = false;

    /**
     * Loaded resources
     */
    private $resources = false;

    /**
     * Path to the template folder
     */
    private $path = false;

    /**
     * Page blocks
     */
    private $blocks = [];

    /**
     * Page variables
     */
    private $pageVars = array();

    /**
     * Template сonstructor
     *
     * @param string $path
     *            Path to template
     * @param string $template
     *            Page layout
     * @param array $blocks
     *            Page blocks
     */
    public function __construct(string $path, string $template = 'index', array $blocks = [])
    {
        $this->path = $path;

        $this->resetLayout($template);

        $this->resources = new \Mezon\Application\TemplateResources();

        $this->blocks = [];

        foreach ($blocks as $blockName) {
            $this->addBlock($blockName);
        }

        // output all blocks in one place
        // but each block can be inserted in {$blockName} places
        $this->setPageVar('content-blocks', implode('', $this->blocks));
    }

    /**
     * Setting page variables
     *
     * @param string $var
     *            Variable name
     * @param mixed $value
     *            Variable value
     */
    public function setPageVar(string $var, $value): void
    {
        $this->pageVars[$var] = $value;
    }

    /**
     * Setting page variables from file's content
     *
     * @param string $var
     *            Variable name
     * @param mixed $path
     *            Path to file
     */
    public function setPageVarFromFile(string $var, string $path): void
    {
        $this->setPageVar($var, file_get_contents($path));
    }

    /**
     * Compiling the page with it's variables
     *
     * @param string $content
     *            Compiling content
     */
    public function compilePageVars(string &$content): void
    {
        foreach ($this->pageVars as $key => $value) {
            if (is_array($value) === false && is_object($value) === false) {
                // only scalars can be substituted in this way
                $content = str_replace('{' . $key . '}', $value, $content);
            }
        }

        $content = \Mezon\TemplateEngine\TemplateEngine::unwrapBlocks($content, $this->pageVars);

        $content = \Mezon\TemplateEngine\TemplateEngine::compileSwitch($content);
    }

    /**
     * Method resets layout
     *
     * @param string $template
     *            Template name
     */
    public function resetLayout(string $template): void
    {
        $template .= '.html';

        if (file_exists($this->path . '/' . $template)) {
            $this->template = file_get_contents($this->path . '/' . $template);
        } elseif (file_exists($this->path . '/res/templates/' . $template)) {
            $this->template = file_get_contents($this->path . '/res/templates/' . $template);
        } else {
            throw (new \Exception('Template file on the path ' . $this->path . ' was not found', - 1));
        }
    }

    /**
     * Method returns compiled page resources
     *
     * @return string Compiled resources includers
     */
    private function _getResources(): string
    {
        $content = '';

        $cSSFiles = $this->resources->getCssFiles();
        foreach ($cSSFiles as $cSSFile) {
            $content .= '
        <link href="' . $cSSFile . '" rel="stylesheet" type="text/css">';
        }

        $jSFiles = $this->resources->getJsFiles();
        foreach ($jSFiles as $jSFile) {
            $content .= '
        <script src="' . $jSFile . '"></script>';
        }

        return $content;
    }

    /**
     * Compile template
     *
     * @return string Compiled template
     */
    public function compile(): string
    {
        $this->setPageVar('resources', $this->_getResources());
        $this->setPageVar('mezon-http-path', \Mezon\Conf\Conf::getConfigValue('@mezon-http-path'));
        $this->setPageVar('service-http-path', \Mezon\Conf\Conf::getConfigValue('@service-http-path'));
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->setPageVar('host', $_SERVER['HTTP_HOST']);
        }

        foreach ($this->blocks as $blockName => $block) {
            $this->setPageVar($blockName, $block);
        }

        $this->compilePageVars($this->template);

        $this->template = preg_replace('/\{[a-zA-z0-9\-]*\}/', '', $this->template);

        return $this->template;
    }

    /**
     * Method returns block's content
     *
     * @param string $blockName
     * @return string block's content
     */
    protected function readBlock(string $blockName): string
    {
        if (file_exists($this->path . '/res/blocks/' . $blockName . '.tpl')) {
            $blockContent = file_get_contents($this->path . '/res/blocks/' . $blockName . '.tpl');
        } elseif (file_exists($this->path . '/blocks/' . $blockName . '.tpl')) {
            $blockContent = file_get_contents($this->path . '/blocks/' . $blockName . '.tpl');
        } else {
            throw (new \Exception('Block ' . $blockName . ' was not found', - 1));
        }

        return $blockContent;
    }

    /**
     * Method adds block to page
     *
     * @param string $blockName
     *            Name of the additing block
     */
    public function addBlock(string $blockName): void
    {
        $this->blocks[$blockName] = $this->readBlock($blockName);
    }

    /**
     * Method returns block's content
     *
     * @param string $blockName
     * @return string block's content
     */
    public function getBlock(string $blockName): string
    {
        return $this->readBlock($blockName);
    }
}
