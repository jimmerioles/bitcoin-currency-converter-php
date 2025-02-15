<?php

declare(strict_types=1);

namespace Jimmerioles\BitcoinCurrencyConverter\Exception;

use Jimmerioles\BitcoinCurrencyConverter\Contracts\ExceptionInterface;
use UnexpectedValueException as BaseUnexpectedValueException;

class UnexpectedValueException extends BaseUnexpectedValueException implements ExceptionInterface {}
