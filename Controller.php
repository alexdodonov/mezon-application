<?php
namespace Mezon\Application;

/**
 * Class Controller
 *
 * @package Mezon
 * @subpackage Controller
 * @author Dodonov A.A.
 * @version v.1.0 (2020/01/12)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base class for all views
 */
class Controller implements \Mezon\Application\ControllerInterface
{

    /**
     * Controllers's name
     *
     * @var string
     */
    protected $controllerName = '';

    /**
     * Constructor
     *
     * @param string $controllerName
     *            Controller name to be executed
     */
    public function __construct(string $controllerName = '')
    {
        $this->controllerName = $controllerName;
    }

    /**
     * Method runs controller
     *
     * @param string ControllerName
     *            Controller name to be run
     * @return mixed result of the controller
     */
    public function run(string $controllerName = '')
    {
        if ($controllerName === '') {
            $controllerName = $this->controllerName;
        }

        if ($controllerName === '') {
            $controllerName = 'Default';
        }

        if (method_exists($this, 'controller' . $controllerName)) {
            return call_user_func([
                $this,
                'controller' . $controllerName
            ]);
        }

        throw (new \Exception('Controller ' . $controllerName . ' was not found'));
    }

    /**
     * Method returns controller name
     *
     * @return string controller name
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }
}
