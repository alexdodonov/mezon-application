<?php
namespace Mezon\Application;

/**
 * Interface ControllerInterface
 *
 * @package Application
 * @subpackage ControllerInterface
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/12)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base interface for all controllers
 */
interface ControllerInterface
{

    /**
     * Method runs controller
     *
     * @param string $controllerName
     *            Controller name to be run
     * @return mixed Controller execution result
     */
    public function run(string $controllerName = '');

    /**
     * Method returns controller's name
     *
     * @return string controller's name
     */
    public function getControllerName(): string;
}
