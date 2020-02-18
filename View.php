<?php
namespace Mezon\Application;

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
    protected $viewName = '';

    /**
     * Constructor
     *
     * @param string $viewName
     *            View name to be rendered
     */
    public function __construct(string $viewName = '')
    {
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
            $viewName = 'default';
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
