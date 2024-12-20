<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Exception;

use InvalidArgumentException as GlobalInvalidArgumentException;
use Jimmerioles\BitcoinCurrencyConverter\Contracts\ExceptionInterface;

class InvalidArgumentException extends GlobalInvalidArgumentException implements ExceptionInterface
{
}
