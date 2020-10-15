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
class View extends ViewBase
{

    /**
     * View's name
     *
     * @var string
     */
    private $viewName = '';

    /**
     * Constructor
     *
     * @param ?HtmlTemplate $template
     *            template
     * @param string $viewName
     *            View name to be rendered
     */
    public function __construct(HtmlTemplate $template = null, string $viewName = '')
    {
        parent::__construct($template);

        $this->viewName = $viewName;
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

        throw (new \Exception('View "' . $viewName . '" was not found', -1));
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
