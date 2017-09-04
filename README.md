# Bitcoin Currency Converter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-codacy]][link-codacy]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple and lightweight bitcoin to currency converter and vice versa based on current exchange rates from your chosen provider: Coinbase, Coindesk, Bitpay and etc.

## Install

Via Composer:

``` bash
$ composer require jimmerioles/bitcoin-currency-converter-php
```

## Usage

#### Convert Bitcoin to any currency (fiat or crypto):

``` php
use Jimmerioles\BitcoinCurrencyConverter\Converter;

$convert = new Converter;
echo $convert->toCurrency('USD', 0.5); // 2000.00
echo $convert->toCurrency('LTC', 0.5); // 10.12345678
```

or use helper for convenience:

``` php
echo to_currency('USD', 0.5); // 2000.00
echo to_currency('LTC', 0.5); // 10.12345678
```

#### Convert any currency (fiat or crypto) to Bitcoin:

``` php
use Jimmerioles\BitcoinCurrencyConverter\Converter;

$convert = new Converter;
echo $convert->toBtc(100, 'USD'); // 0.12345678
echo $convert->toBtc(20, 'LTC');  // 1.12345678
```

or use helper for convenience:

``` php
echo to_btc(100, 'USD'); // 0.12345678
echo to_btc(20, 'LTC');  // 2.12345678
```

#### Specifying exchange rates provider:

```php
use Jimmerioles\BitcoinCurrencyConverter\Converter;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoinbaseProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\CoindeskProvider;
use Jimmerioles\BitcoinCurrencyConverter\Provider\BitpayProvider;

$convert = Converter(new CoinbaseProvider);
$convert = Converter(new CoindeskProvider);
$convert = Converter(new BitpayProvider);
```

## Change log

Please see [CHANGELOG](https://github.com/jimmerioles/bitcoin-currency-converter-php/releases) for more information on what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Open for suggestions and requests. Please request an [issue](https://github.com/jimmerioles/bitcoin-currency-converter-php/issues/new) or do [pull requests](https://github.com/jimmerioles/bitcoin-currency-converter-php/pull/new/master).

## Security

If you discover any security related issues, please email jimwisleymerioles@gmail.com instead of using the issue tracker.

## Credits

- [Jim Merioles][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/jimmerioles/bitcoin-currency-converter-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jimmerioles/bitcoin-currency-converter-php/master.svg?style=flat-square
[ico-codacy]: https://img.shields.io/codacy/coverage/77c6cbd35c6c4b06b786a64dfb6b362c.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/codacy/grade/77c6cbd35c6c4b06b786a64dfb6b362c.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jimmerioles/bitcoin-currency-converter-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jimmerioles/bitcoin-currency-converter-php
[link-travis]: https://travis-ci.org/jimmerioles/bitcoin-currency-converter-php
[link-codacy]: https://www.codacy.com/app/jimmerioles/bitcoin-currency-converter-php
[link-code-quality]: https://www.codacy.com/app/jimmerioles/bitcoin-currency-converter-php
[link-downloads]: https://packagist.org/packages/jimmerioles/bitcoin-currency-converter-php/stats
[link-author]: https://twitter.com/jimmerioles
[link-contributors]: https://github.com/jimmerioles/bitcoin-currency-converter-php/graphs/contributors
