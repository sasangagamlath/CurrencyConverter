<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);


namespace Charaka\CurrencyConverter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const string XML_PATH_API_URL = 'currency_converter/general/api_url';
    public const string XML_PATH_DEFAULT_CURRENCY_FROM = 'currency_converter/general/default_currency_from';
    public const string XML_PATH_DEFAULT_CURRENCY_TO = 'currency_converter/general/default_currency_to';
    public const string XML_PATH_CURL_TIME_OUT = 'currency_converter/general/curl_time_out';
    public const string XML_PATH__ASSET_TYPE = 'currency_converter/general/asset_type';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_API_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get default currency from
     *
     * @return string
     */
    public function getDefaultCurrencyFrom(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEFAULT_CURRENCY_FROM, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get default currency to
     *
     * @return string
     */
    public function getDefaultCurrencyTo(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEFAULT_CURRENCY_TO, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get curl time out
     *
     * @return int
     */
    public function getCurlTimeout(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_CURL_TIME_OUT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Show asset type
     *
     * @return int
     */
    public function showOnlyCurrencyAssetType(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH__ASSET_TYPE, ScopeInterface::SCOPE_STORE);
    }
}
