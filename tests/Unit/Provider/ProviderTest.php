<?php

namespace Test\Unit\Provider;

use Test\TestCase;

abstract class ProviderTest extends TestCase
{
    protected ?string $stubResponse = null;

    protected function getStubResponse(string $fixturesPath = ''): string
    {
        if (! $this->stubResponse) {
            $parsed = file_get_contents(project_root_path($fixturesPath));

            if (!$parsed) {
                return '{}';
            }

            $this->stubResponse = $parsed;
        }

        return $this->stubResponse;
    }
}
