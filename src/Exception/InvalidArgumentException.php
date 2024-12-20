<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Exception;

use InvalidArgumentException as GlobalInvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Contracts\ExceptionInterface;

class InvalidArgumentException extends GlobalInvalidArgumentException implements ExceptionInterface
{
}
