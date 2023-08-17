<?php
namespace Mezon\Application;

class Files
{

    /**
     * Root path
     *
     * @var string
     */
    public static $rootPath = '';

    /**
     * Getting root path
     *
     * @return string
     */
    public static function getRootPath(): string
    {
        if (self::$rootPath === '') {
            self::$rootPath = dirname(__DIR__, 5);
        }

        return self::$rootPath;
    }

    /**
     * Loading application file from the root directory
     *
     * @param string $relativeFilePath
     *            relative path to the file
     * @return string file contents
     */
    public static function getFile(string $relativeFilePath): string
    {
        // go to the root directory of the project
        return file_get_contents(self::getRootPath() . '/' . $relativeFilePath);
    }
}
