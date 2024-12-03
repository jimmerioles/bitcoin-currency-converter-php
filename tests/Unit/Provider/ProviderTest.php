<?php

namespace Test\Unit\Provider;

use Test\TestCase;

abstract class ProviderTest extends TestCase
{
    protected $stubResponse;

    protected function getStubResponse($fixturesPath = '')
    {
        if (! $this->stubResponse) {
            $this->stubResponse = file_get_contents(project_root_path($fixturesPath));
        }

        return $this->stubResponse;
    }
}