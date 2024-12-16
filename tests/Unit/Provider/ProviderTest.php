<?php

namespace Test\Unit\Provider;

use Test\TestCase;

abstract class ProviderTest extends TestCase
{
    protected string|bool $stubResponse = false;

    protected function getStubResponse(string $fixturesPath = ''): string|bool
    {
        if (! $this->stubResponse) {
            $this->stubResponse = file_get_contents(project_root_path($fixturesPath));
        }

        return $this->stubResponse;
    }
}
