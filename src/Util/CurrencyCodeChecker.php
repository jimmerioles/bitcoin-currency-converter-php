<?php

namespace Jimmerioles\BitcoinCurrencyConverter\Util;

class CurrencyCodeChecker
{
    /**
     * List of all fiat currencies.
     *
     * @var array
     */
    protected $fiatCurrencyCodes = [
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
        'VND', 'VUV', 'WST', 'YER', 'ZAR', 'ZMW', 'ZWL', 'CNH', 'CNT', 'GGP', 'IMP', 'JEP', 'KID', 'NIS',
        'NTD', 'PRB', 'SLS', 'TVD'
    ];

    /**
     * List of all crypto currencies.
     *
     * @var array
     */
    protected $cryptoCurrencyCodes = [
        'AUR', 'BIS', 'BTC', 'XBT', 'BCH', 'BCC', 'BC', 'BURST', 'DASH', 'DOGE', 'XDG', 'XDN', 'EMC', 'ETH',
        'ETC', 'GRC', 'IOT', 'MIOTA', 'LTC', 'MZC', 'XMR', 'NMC', 'XEM', 'NXT', 'MSC', 'PPC', 'POT', 'XPM',
        'XRP', 'SIL', 'STC', 'AMP', 'TIT', 'UBQ', 'VTC', 'ZEC', 'XBC', 'XLM', 'XZC', 'NEO', 'LSK', 'STRAT',
        'WAVES', 'BCN', 'HSR', 'BTS', 'STEEM', 'KMD', 'ARK', 'FCT', 'SC', 'GBYTE', 'PIVX', 'DCR', 'DGB',
        'NXS', 'BTCD', 'GAME', 'SYS', 'BLOCK', 'XVG', 'NAV', 'LKK', 'UBQ', 'PART', 'NLC2', 'GXS', 'NLG', 'DCT',
        'FRST', 'RISE', 'EMC', 'LEO', 'XEL', 'IOC', 'XAS', 'ADK', 'PPC', 'RDD', 'WTC', 'FAIR', 'VTC', 'XCP',
        'VIA', 'ETP', 'MONA', 'EXP', 'CLOACK', 'OK', 'ION', 'SIB', 'TCC', 'EB3', 'LBC', 'RADS', 'BAY', 'CRW',
        'POT', 'CLAM', 'PPY', 'SKY', 'ZEN', 'UNO', 'MUE', 'SHIFT', 'BLK', 'SPR', 'SLS', 'GOLOS', 'OMNI', 'YBC',
        'ENRG', 'MOON', 'RBY', 'VRC', 'XRB', 'ECN', 'DMD', 'EDR'
    ];

    /**
     * Check if crypto currency.
     *
     * @param  string  $currencyCode
     */
    public function isCryptoCurrency($currencyCode): bool
    {
        return in_array(strtoupper($currencyCode), $this->cryptoCurrencyCodes);
    }

    /**
     * Check if fiat currency.
     *
     * @param  string  $currencyCode
     */
    public function isFiatCurrency($currencyCode): bool
    {
        return in_array(strtoupper($currencyCode), $this->fiatCurrencyCodes);
    }

    /**
     * Check if currency code.
     *
     * @param  string  $currencyCode
     */
    public function isCurrencyCode($currencyCode): bool
    {
        if ($this->isFiatCurrency($currencyCode)) {
            return true;
        }
        return $this->isCryptoCurrency($currencyCode);
    }
}
