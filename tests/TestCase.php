<?php

declare(strict_types=1);

namespace Test;

use Illuminate\Filesystem\Filesystem;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function tearDown(): void
    {
        if (class_exists('Mockery')) {
            \Mockery::close();
        }

        (new Filesystem())->deleteDirectory(project_root_path('cache'));
    }
}
