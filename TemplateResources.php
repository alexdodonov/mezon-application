<?php
namespace Mezon\Application;

/**
 * Class TemplateResources
 *
 * @package Mezon
 * @subpackage TemplateResources
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class collects resources for page.
 *
 * Any including components can add to the page their own resources without having access to the application or template.
 */
class TemplateResources
{

    /**
     * Custom CSS files to be included.
     */
    private static $cSSFiles = false;

    /**
     * Custom JS files to be included.
     */
    private static $jSFiles = false;

    /**
     * Constructor.
     */
    function __construct()
    {
        if (self::$cSSFiles === false) {
            self::$cSSFiles = [];
        }
        if (self::$jSFiles === false) {
            self::$jSFiles = [];
        }
    }

    /**
     * Additing single CSS file
     *
     * @param string $cSSFile
     *            CSS file
     */
    function addCssFile(string $cSSFile)
    {
        // additing only unique paths
        if (array_search($cSSFile, self::$cSSFiles) === false) {
            self::$cSSFiles[] = \Mezon\Conf\Conf::expandString($cSSFile);
        }
    }

    /**
     * Additing multyple CSS files
     *
     * @param array $cSSFiles
     *            CSS files
     */
    function addCssFiles(array $cSSFiles)
    {
        foreach ($cSSFiles as $cSSFile) {
            $this->addCssFile($cSSFile);
        }
    }

    /**
     * Method returning added CSS files
     */
    function getCssFiles()
    {
        return self::$cSSFiles;
    }

    /**
     * Additing single CSS file
     *
     * @param string $jSFile
     *            JS file
     */
    function addJsFile($jSFile)
    {
        // additing only unique paths
        if (array_search($jSFile, self::$jSFiles) === false) {
            self::$jSFiles[] = \Mezon\Conf\Conf::expandString($jSFile);
        }
    }

    /**
     * Additing multyple CSS files
     *
     * @param array $jSFiles
     *            JS files
     */
    function addJsFiles(array $jSFiles)
    {
        foreach ($jSFiles as $jSFile) {
            $this->addJsFile($jSFile);
        }
    }

    /**
     * Method returning added JS files.
     */
    function getJsFiles()
    {
        return self::$jSFiles;
    }

    /**
     * Method clears loaded resources.
     */
    function clear()
    {
        self::$cSSFiles = [];

        self::$jSFiles = [];
    }
}
