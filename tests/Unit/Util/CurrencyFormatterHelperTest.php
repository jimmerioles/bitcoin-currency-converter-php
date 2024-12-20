<?php

declare(strict_types=1);

namespace Test\Unit\Util;

use Test\TestCase;

class CurrencyFormatterHelperTest extends TestCase
{
    public function test_can_format_to_currency(): void
    {
        $this->assertEquals(123.46, format_to_currency('USD', 123.456789123456));
        $this->assertEquals(123.45678912, format_to_currency('BTC', 123.456789123456));
    }

    public function test_can_format_to_currency_in_lowercaps_currency_code(): void
    {
        $this->assertEquals(123.46, format_to_currency('usd', 123.456789123456));
        $this->assertEquals(123.45678912, format_to_currency('btc', 123.456789123456));
    }

    public function test_format_to_currency_throws_exception_when_passed_with_invalid_currency_code_argument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$currencyCode not valid currency code, 'FOO' given.");

        format_to_currency('FOO', 123.456789123);
    }
}
