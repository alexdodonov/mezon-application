<?php
namespace Mezon\Application;

use Mezon\HtmlTemplate\HtmlTemplate;

class ActionBuilder
{

    /**
     * Ignore config keyu
     *
     * @return callable factory method
     */
    public static function ignoreKey(): callable
    {
        return function (): void {
            // do nothind
        };
    }

    /**
     * Method resets layout
     *
     *  @param $template template 
     * @param string $value
     *            new layout
     * @return callable factory method
     */
    public static function resetLayout(HtmlTemplate $template, string $value): callable
    {
        return function () use ($template, $value) {
            $template->resetLayout($value);
        };
    }
}
