<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Contracts\ExceptionInterface;

class InvalidArgumentException extends BaseInvalidArgumentException implements ExceptionInterface
{
}
