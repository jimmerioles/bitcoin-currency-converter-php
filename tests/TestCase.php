<?php

namespace Test;

use Illuminate\Filesystem\Filesystem;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function tearDown(): void
    {
        if (class_exists('Mockery')) {
            \Mockery::close();
        }

        (new Filesystem())->deleteDirectory(project_root_path('cache'));
    }
}
