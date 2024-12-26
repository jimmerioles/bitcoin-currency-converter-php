# Bitcoin Currency Converter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![PHP 7.* Tests][ico-github-actions]][link-github-actions]
[![Test Coverage][ico-coverage]][link-coverage]
[![Maintainability][ico-maintainability]][link-maintainability]
[![Total Downloads][ico-downloads]][link-downloads]

This library helps developers that need to easily convert bitcoin to fiat currency(ISO 4217) or to another cryptocurrency and vice versa from your exchange rates provider of choice.

Available exchange rates providers are:
* [Coinbase][link-coinbase-rates]
* [Coindesk][link-coindesk-rates]
* [Bitpay][link-bitpay-rates]

If you have any request for other exchange rates provider or other features that you would like for me to add see [Contributing][link-contributing].

## Features

It is simple, lightweight, extensible, framework agnostic and fast.

* You can convert Bitcoin to any currency (ISO 4217 fiat or another cryptocurrency)
* You can convert any currency (ISO 4217 fiat or another cryptocurrency) to Bitcoin
* It supports different exchange rates providers: Coinbase, Coindesk, Bitpay
* It has baked-in caching (PSR16 compliant, swappable with your own or your framework's)

## Install

Lets begin by installing the library by Composer:

``` bash
$ composer require jimmerioles/bitcoin-currency-converter-php
```

## Usage

#### You can then convert Bitcoin to any currency (ISO 4217 fiat or crypto) by:

``` php
use Jimmerioles\BitcoinCurrencyConverter\Converter;

$convert = new Converter;              // uses Coinbase as default provider
echo $convert->toCurrency('USD', 0.5); // 2000.00
echo $convert->toCurrency('LTC', 0.5); // 10.12345678
```

or you can use the helper function for convenience:

``` php
// uses Coinbase as default provider
echo to_currency('USD', 0.5); // 2000.00
echo to_currency('LTC', 0.5); // 10.12345678
```

#### You can also convert any currency (ISO 4217 fiat or crypto) to Bitcoin:

``` php
use Jimmerioles\BitcoinCurrencyConverter\Converter;

$convert = new Converter;         // uses Coinbase as default provider
echo $convert->toBtc(100, 'USD'); // 0.12345678
echo $convert->toBtc(20, 'LTC');  // 1.12345678
```

and it also has its helper function for convenience:

``` php
// uses Coinbase as default provider
echo to_btc(100, 'USD'); // 0.12345678
echo to_btc(20, 'LTC');  // 2.12345678
```

#### You can use different exchange rates from providers:

``` php
use Jimmerioles\BitcoinCurrencyConverter\Converter;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\BitpayProvider;

$convert = new Converter(new CoinbaseProvider);
$convert = new Converter(new CoindeskProvider);
$convert = new Converter(new BitpayProvider);
```

or if you prefer to use the helper functions:

``` php
echo to_currency('USD', 0.5, new CoindeskProvider); // 2000.00
echo to_currency('LTC', 0.5, new BitpayProvider);   // 10.12345678
echo to_btc(100, 'USD', new CoindeskProvider);      // 0.12345678
echo to_btc(20, 'LTC', new BitpayProvider);         // 2.12345678
```

#### You can specify cache expire time (ttl) on provider by:

``` php
new CoinbaseProvider($httpClient, $psr16CacheImplementation, 5); // cache expires in 5mins, defaults to 60mins
```

## Change log

Please see [CHANGELOG][link-changelog] for more information on what has changed recently.

## Testing

``` bash
$ phpunit
```

#### Show full specs and features:

``` bash
$ phpunit --testdox
```

## Contributing

Open for suggestions and requests. Please request through [issue][link-issue] or [pull requests][link-pull-request].

## Security

If you discover any security related issues, please email jimwisleymerioles@gmail.com instead of using the issue tracker.

## Credits

- [Jim Merioles][link-author]
- [Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

### Want to show some :heart:?

Let's find Satoshi Nakamoto! | or let's have a :coffee:
------------ | ------------
![Donate Bitcoin][ico-bitcoin] | ![Donate Ethereum][ico-ethereum]


[ico-version]: https://img.shields.io/packagist/v/jimmerioles/bitcoin-currency-converter-php.svg?style=flat
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
[ico-github-actions]: https://github.com/jimmerioles/bitcoin-currency-converter-php/actions/workflows/php8.0-8.2-tests.yml/badge.svg
[ico-coverage]: https://api.codeclimate.com/v1/badges/438413be3c404775866c/test_coverage
[ico-maintainability]: https://api.codeclimate.com/v1/badges/438413be3c404775866c/maintainability
[ico-downloads]: https://img.shields.io/packagist/dt/jimmerioles/bitcoin-currency-converter-php.svg?style=flat
[ico-bitcoin]: https://img.shields.io/badge/Bitcoin-1KBT3Mzsr2dZqhQqNYx4gum8Yuyd61UzNk-blue.svg?style=flat
[ico-ethereum]: https://img.shields.io/badge/Ethereum-0x7896E9C4118e495Eb7001a847BBFA3C29Dfc69d9-blue.svg?style=flat

[link-packagist]: https://packagist.org/packages/jimmerioles/bitcoin-currency-converter-php
[link-github-actions]: https://github.com/jimmerioles/bitcoin-currency-converter-php/actions/workflows/php8.0-8.2-tests.yml
[link-coverage]: https://codeclimate.com/github/jimmerioles/bitcoin-currency-converter-php/test_coverage
[link-maintainability]: https://codeclimate.com/github/jimmerioles/bitcoin-currency-converter-php/maintainability
[link-downloads]: https://packagist.org/packages/jimmerioles/bitcoin-currency-converter-php/stats
[link-author]: https://twitter.com/jimmerioles
[link-contributors]: https://github.com/jimmerioles/bitcoin-currency-converter-php/graphs/contributors
[link-coinbase-rates]: https://www.coinbase.com/charts
[link-coindesk-rates]: https://www.coindesk.com/price
[link-bitpay-rates]: https://bitpay.com/bitcoin-exchange-rates
[link-changelog]: https://github.com/jimmerioles/bitcoin-currency-converter-php/blob/master/CHANGELOG.md
[link-issue]: https://github.com/jimmerioles/bitcoin-currency-converter-php/issues/new
[link-pull-request]: https://github.com/jimmerioles/bitcoin-currency-converter-php/pull/new/master
[link-contributing]: https://github.com/jimmerioles/bitcoin-currency-converter-php#contributing
