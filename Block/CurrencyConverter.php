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
        return array_column($this->apiCall->getCurrencies(), 'name', 'iso_code');
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams(): array
    {
        $request = $this->getRequest();

        $from = strtoupper(trim((string)$request->getParam('from', $this->config->getDefaultCurrencyFrom())));
        $to = strtoupper(trim((string)$request->getParam('to', $this->config->getDefaultCurrencyTo())));
        $action = (string)$request->getParam('action');
        $amount = (string)$request->getParam('amount', 1);

        if ($action === 'swap') {
            [$from, $to] = [$to, $from];
        }

        return [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'days' => 30,
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
        $latestRate = $this->apiCall->getLatestRate($params['from'], $params['to']);
        return (int)$params['amount'] * $latestRate;
    }
}
