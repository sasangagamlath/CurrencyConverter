<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);

namespace Charaka\CurrencyConverter\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Charaka\CurrencyConverter\Model\ApiCall;
use Charaka\CurrencyConverter\Model\Config;
use Charaka\CurrencyConverter\Model\Constants;

class CurrencyConverter extends Template
{

    /**
     * @param Context $context
     * @param ApiCall $apiCall
     * @param Config $config
     */
    public function __construct(
        Context                  $context,
        private readonly ApiCall $apiCall,
        private readonly Config  $config,
    ) {
        parent::__construct($context);
    }

    /**
     * Get currencies
     *
     * @return array
     */
    public function getCurrencies(): array
    {
        $onlyCurrency = $this->config->showOnlyCurrencyAssetType();

        $showCurrencies = [];
        foreach ($this->apiCall->getCurrencies() as $currency) {
            if ($onlyCurrency && $currency[Constants::FIELD_TYPE] != Constants::FIELD_ASSET_TYPE_CURRENCY) {
                continue;
            }
            $showCurrencies[] = $currency;
        }
        return array_column($showCurrencies, Constants::FIELD_NAME, Constants::FIELD_ISO_CODE);
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams(): array
    {
        $request = $this->getRequest();

        $from = strtoupper(trim((string)$request->getParam(
            Constants::PARAM_FIELD_FROM,
            $this->config->getDefaultCurrencyFrom()
        )));
        $to = strtoupper(trim((string)$request->getParam(
            Constants::PARAM_FIELD_TO,
            $this->config->getDefaultCurrencyTo()
        )));

        $amount = (string)$request->getParam(Constants::PARAM_FIELD_AMOUNT, 1);
        $action = (string)$request->getParam(Constants::PARAM_FIELD_ACTION);

        if ($action === Constants::PARAM_FIELD_SWAP) {
            [$from, $to] = [$to, $from];
        }

        return [
            Constants::PARAM_FIELD_FROM => $from,
            Constants::PARAM_FIELD_TO => $to,
            Constants::PARAM_FIELD_AMOUNT => $amount,
            Constants::PARAM_FIELD_DAYS => $this->config->rateHistoryDateRange(),
        ];
    }

    /**
     * Get converted amount
     *
     * @return float|null
     */
    public function getConvertedAmount(): ?float
    {
        $params = $this->getParams();
        $latestRate = $this->apiCall->getLatestRate(
            $params[Constants::PARAM_FIELD_FROM],
            $params[Constants::PARAM_FIELD_TO]
        );
        return (int)$params['amount'] * $latestRate;
    }

    /**
     * Get history date using api
     *
     * @return array
     */
    public function getHistoryData(): array
    {
        $params = $this->getParams();
        return $this->apiCall->getHistoricalRates(
            $params[Constants::PARAM_FIELD_FROM],
            $params[Constants::PARAM_FIELD_TO],
            $params[Constants::PARAM_FIELD_DAYS]
        );
    }

    /**
     * Generate data to polyline points
     *
     * @param array $history
     * @return string
     */
    public function generateChartData(array $history): string
    {
        $rates = array_values($history);
        $count = count($rates);

        $min = min($rates);
        $max = max($rates);
        $range = ($max - $min) ?: 1.0;
        $points = [];

        $width = 600;
        $height = 160;
        foreach ($rates as $index => $rate) {
            $x = ($index / ($count - 1)) * $width;
            $y = $height - (($rate - $min) / $range) * $height;
            $points[] = round($x, 1) . ',' . round($y, 1);
        }

        return implode(' ', $points);
    }
}
