<?php
namespace Mezon\Application\Tests;

/**
 * View class
 *
 * @author Dodonov A.A.
 */
class TestView extends \Mezon\Application\View
{

    public function __construct(string $content)
    {
        parent::__construct(null, 'default');

        $this->content = $content;
    }

    public function render(string $viewName = ''): string
    {
        return $this->content;
    }
}
