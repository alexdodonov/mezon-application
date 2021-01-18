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
     * @param $template template
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

    /**
     * Overriding defined config
     *
     * @param string $path
     *            path to the current config
     * @param array $config
     *            config itself
     */
    public static function constructOverrideHandler(string $path, array &$config): void
    {
        if (isset($config['override'])) {

            $path = pathinfo($path, PATHINFO_DIRNAME);

            $baseConfig = json_decode(file_get_contents($path . '/' . $config['override']), true);

            $config = array_merge($baseConfig, $config);
        }
    }

    /**
     * Method returns fabric method for action processing
     *
     * @param CommonApplication $app
     *            application
     * @param string $key
     *            config key name
     * @param mixed $value
     *            config key value
     * @return callable|NULL callback
     */
    public static function getActionBuilderMethod(CommonApplication $app, string $key, $value): ?callable
    {
        if ($key === 'override') {
            return ActionBuilder::ignoreKey();
        } elseif ($key === 'layout') {
            return ActionBuilder::resetLayout($app->getTemplate(), $value);
        }

        return null;
    }

    /**
     * Constructing view
     *
     * @param CommonApplication $app
     *            application
     * @param array $result
     *            compiled result
     * @param string $key
     *            config key
     * @param mixed $value
     *            config value
     * @param array $views
     *            list of views
     */
    public static function constructOtherView(CommonApplication $app, array &$result, string $key, $value, array &$views): void
    {
        // any other view
        if (isset($value['name'])) {
            $views[$key] = new $value['class']($app->getTemplate(), $value['name']);
        } else {
            $views[$key] = new $value['class']($app->getTemplate());
        }

        foreach ($value as $configKey => $configValue) {
            if (! in_array($configKey, [
                'class',
                'name',
                'placeholder'
            ])) {
                $views[$key]->setViewParameter($configKey, $configValue, true);
            }
        }

        $result[$value['placeholder']] = $views[$key];
    }
}
