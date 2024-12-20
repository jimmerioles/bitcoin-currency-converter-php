<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Exception;

use Jimmerioles\BitcoinCurrencyConverter\Contracts\ExceptionInterface;
use UnexpectedValueException as GlobalUnexpectedValueException;

class UnexpectedValueException extends GlobalUnexpectedValueException implements ExceptionInterface
{
}
