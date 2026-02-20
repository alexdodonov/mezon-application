<?php
namespace Mezon\Application\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class ApplicationTests
 *
 * @package Application
 * @subpackage ApplicationTests
 * @author Dodonov A.A.
 * @version v.1.0 (2026/02/20)
 * @copyright Copyright (c) 2026, aeon.org
 */

/**
 * Predefined settings for the Application tests
 *
 * @author Dodonov A.A.
 */
class ApplicationTests extends TestCase
{

    /**
     *
     * {@inheritdoc}
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        // context setup
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }
}