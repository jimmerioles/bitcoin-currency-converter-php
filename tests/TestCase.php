<?php

namespace Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        if (class_exists('Mockery')) {
            \Mockery::close();
        }
    }
}
