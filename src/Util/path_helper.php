<?php

use Composer\Factory;

if (! function_exists('project_root_path')) {

    /**
     * Get project root path.
     *
     * @param  string $path
     * @return string
     */
    function project_root_path($path = '')
    {
        return dirname(Factory::getComposerFile()) . DIRECTORY_SEPARATOR . $path;
    }
}
