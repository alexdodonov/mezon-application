<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;

/**
 * Class ViewStatic
 *
 * @package Mezon
 * @subpackage View
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/06)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class outputs static block by it's name
 */
class ViewStatic extends ViewBase
{

    /**
     * Block name
     *
     * @var string
     */
    private $blockName = '';

    /**
     * Constructor
     *
     * @param HtmlTemplate $template
     *            template
     * @param string $blockName
     *            View's block name to be rendered
     */
    public function __construct(HtmlTemplate $template, string $blockName = '')
    {
        parent::__construct($template);

        $this->blockName = $blockName;
    }

    /**
     * Method renders content from view
     *
     * @param string $blockName
     *            name of the block to be rendered
     * @return string Generated content
     */
    public function render(string $blockName = ''): string
    {
        if ($blockName === '') {
            $blockName = $this->blockName;
        }

        if ($blockName === '') {
            throw (new \Exception('Block name must be set', - 1));
        }

        $content = $this->getTemplate()->getBlock($blockName);

        $this->getTemplate()->compilePageVars($content);

        return $content;
    }

    /**
     * Method returns view name
     *
     * @return string view name
     */
    public function getViewName(): string
    {
        return $this->blockName;
    }
}
