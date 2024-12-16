<?php

use Composer\Factory;

if (! function_exists('project_root_path')) {
    /**
     * Get project root path.
     */
    function project_root_path(string $path = ''): string
    {
        return dirname(Factory::getComposerFile()) . DIRECTORY_SEPARATOR . $path;
    }
}
