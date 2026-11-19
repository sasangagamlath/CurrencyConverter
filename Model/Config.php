<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);


namespace Charaka\CurrencyConverter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Charaka\CurrencyConverter\Model\Constants;

class Config
{

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
        return (string)$this->scopeConfig->getValue(Constants::XML_PATH_API_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get default currency from
     *
     * @return string
     */
    public function getDefaultCurrencyFrom(): string
    {
        return (string)$this->scopeConfig->getValue(
            Constants::XML_PATH_DEFAULT_CURRENCY_FROM,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get default currency to
     *
     * @return string
     */
    public function getDefaultCurrencyTo(): string
    {
        return (string)$this->scopeConfig->getValue(
            Constants::XML_PATH_DEFAULT_CURRENCY_TO,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get curl time out
     *
     * @return int
     */
    public function getCurlTimeout(): int
    {
        return (int)$this->scopeConfig->getValue(
            Constants::XML_PATH_CURL_TIME_OUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Show asset type
     *
     * @return int
     */
    public function showOnlyCurrencyAssetType(): int
    {
        return (int)$this->scopeConfig->getValue(Constants::XML_PATH_ASSET_TYPE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Rate history date range
     *
     * @return int
     */
    public function rateHistoryDateRange(): int
    {
        return (int)$this->scopeConfig->getValue(
            Constants::XML_PATH_RATE_HISTORY_DATE_RANGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is enable currency conversion page
     *
     * @return int
     */
    public function isEnableCurrencyConversionPage(): int
    {
        return (int)$this->scopeConfig->getValue(Constants::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Log api calls
     *
     * @return int
     */
    public function logApiCalls(): int
    {
        return (int)$this->scopeConfig->getValue(Constants::XML_PATH_DEBUG_LOG, ScopeInterface::SCOPE_STORE);
    }
}
