<?php

namespace Mezon\Application\Tests;

/**
 * Application for testing purposes.
 */
class TestApplication2 extends \Mezon\Application\Application
{

    /**
     * 
     * @var integer
     */
    var $counter = 0;

    /**
     * Method loads routes from config file in *.php or *.json format
     *
     * @param string $configPath
     *            Path of the config for routes
     */
    public function loadRoutesFromConfig(string $configPath): void
    {
        $this->counter ++;
    }
}
