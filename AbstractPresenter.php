<?php
namespace Mezon\Application;

/**
 * Interface PresenterInterface
 *
 * @package Application
 * @subpackage PresenterInterface
 * @author Dodonov A.A.
 * @version v.1.0 (2020/06/23)
 * @copyright Copyright (c) 2020, aeon.org
 */

/**
 * Base class for all presenters
 */
abstract class AbstractPresenter implements PresenterInterface
{

    /**
     * Presenter's name
     *
     * @var string
     */
    private $presenterName = '';

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function getPresenterName(): string
    {
        return $this->presenterName;
    }

    /**
     * Method returns presenter's name
     *
     * @return string presenter's name
     */
    public function setPresenterName(string $presenterName): void
    {
        $this->presenterName = $presenterName;
    }
}
