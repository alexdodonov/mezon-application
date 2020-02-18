<?php
namespace Mezon\Application;

/**
 * Interface ViewInterface
 *
 * @package Application
 * @subpackage ViewInterface
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/12)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base interface for all views
 */
interface ViewInterface
{

    /**
     * Method renders content from view
     *
     * @param string $viewName
     *            View name to be rendered
     * @return string Generated content
     */
    public function render(string $viewName = ''): string;

    /**
     * Method returns view name
     *
     * @return string view name
     */
    public function getViewName(): string;
}
