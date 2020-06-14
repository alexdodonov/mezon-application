<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;

/**
 * Class ViewBase
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
abstract class ViewBase implements \Mezon\Application\ViewInterface
{

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
     */
    public function __construct(HtmlTemplate $template = null)
    {
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
}
