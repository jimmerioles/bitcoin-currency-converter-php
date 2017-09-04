<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Util;

class CurrencyCodeChecker
{
    /**
     * List of all fiat currencies.
     *
     * @var array
     */
    protected $fiatCurrencyCodes = [ //TODO: verify list
        'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN',
        'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF',
        'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF',
        'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD',
        'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK',
        'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP',
        'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR',
        'MWK', 'MXN', 'MXV', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN',
        'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG',
        'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT',
        'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'UYI', 'UYU', 'UZS', 'VEF',
        'VND', 'VUV', 'WST', 'YER', 'ZAR', 'ZMW', 'ZWL'
    ];

    /**
     * List of all crypto currencies.
     *
     * @var array
     */
    protected $cryptoCurrencyCodes = [ //TODO: verify list
        'AUR', 'BTC', 'XBT', 'BCH', 'BCC', 'BC', 'BURST', 'DASH', 'DOGE', 'XDG', 'XDN', 'EMC', 'ETH', 'ETC',
        'GRC', 'IOT', 'MIOTA', 'LTC', 'MZC', 'XMR', 'NMC', 'XEM', 'NXT', 'MSC', 'PPC', 'POT', 'XPM', 'XRP',
        'SIL', 'STC', 'AMP', 'TIT', 'UBQ', 'VTC', 'ZEC', 'XBC', 'XLM', 'XZC'
    ];

    /**
     * Check if crypto currency.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public function isCryptoCurrency($currencyCode)
    {
        return in_array(strtoupper($currencyCode), $this->cryptoCurrencyCodes);
    }

    /**
     * Check if fiat currency.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public function isFiatCurrency($currencyCode)
    {
        return in_array(strtoupper($currencyCode), $this->fiatCurrencyCodes);
    }

    /**
     * Check if currency code.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public function isCurrencyCode($currencyCode)
    {
        return $this->isFiatCurrency($currencyCode) || $this->isCryptoCurrency($currencyCode);
    }
}
