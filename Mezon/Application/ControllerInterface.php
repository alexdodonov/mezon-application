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
 * 
 * @deprecated since 2020-06-26
 */
abstract class ControllerInterface extends AbstractPresenter
{

    /**
     * Method runs controller
     *
     * @param string $controllerName
     *            Controller name to be run
     * @return mixed Controller execution result
     */
    abstract public function run(string $controllerName = '');

    /**
     * Method returns controller's name
     *
     * @return string controller's name
     */
    public function getControllerName(): string
    {
        return $this->getPresenterName();
    }

    /**
     * Method sets controller's name
     *
     * @param string $controllerName
     *            controller's name
     */
    public function setControllerName(string $controllerName): void
    {
        $this->setPresenterName($controllerName);
    }
}
