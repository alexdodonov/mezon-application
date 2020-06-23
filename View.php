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
     * Presenter
     *
     * @var Presenter
     */
    private $presenter = null;

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
     * @param
     *            ?Presenter presenter
     */
    public function __construct(HtmlTemplate $template = null, string $viewName = '', Presenter $presenter = null)
    {
        parent::__construct($template);

        $this->presenter = $presenter;

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

    /**
     * Method return presenter or throws exception if it was not setup
     *
     * @return Presenter presenter
     */
    public function getPresenter(): Presenter
    {
        if ($this->presenter === null) {
            throw (new \Exception('Presenter was not setup', - 1));
        }

        return $this->presenter;//@codeCoverageIgnoreStart
    }//@codeCoverageIgnoreEnd
}
