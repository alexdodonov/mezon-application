<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;

/**
 * Class View
 *
 * @package Mezon
 * @subpackage View
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/06)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class for all views
 */
class View implements \Mezon\Application\ViewInterface
{

    /**
     * View's name
     *
     * @var string
     */
    private $viewName = '';

    /**
     * Active template
     *
     * @var HtmlTemplate
     */
    private $template = null;

    /**
     * Constructor
     *
     * @param HtmlTemplate $template
     *            template
     * @param string $viewName
     *            View name to be rendered
     */
    public function __construct(HtmlTemplate $template = null, string $viewName = '')
    {
        $this->viewName = $viewName;

        $this->template = $template;
    }

    /**
     * Method returns template
     *
     * @return HtmlTemplate template
     */
    public function getTemplate(): HtmlTemplate
    {
        if ($this->template === null) {
            throw (new \Exception('Template was not set for the view', - 1));
        }

        return $this->template;
    }

    /**
     * Method renders content from view
     *
     * @param string $viewName
     *            View name to be rendered
     * @return string Generated content
     */
    public function render(string $viewName = ''): string
    {
        if ($viewName === '') {
            $viewName = $this->viewName;
        }

        if ($viewName === '') {
            $viewName = 'Default';
        }

        if (method_exists($this, 'view' . $viewName)) {
            return call_user_func([
                $this,
                'view' . $viewName
            ]);
        }

        throw (new \Exception('View ' . $viewName . ' was not found'));
    }

    /**
     * Method returns view name
     *
     * @return string view name
     */
    public function getViewName(): string
    {
        return $this->viewName;
    }
}
