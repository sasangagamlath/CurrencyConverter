<?php
/**
 * Copyright © 2026 All rights reserved
 * @author Charaka Gamlath <sasangagamlath@gmail.com>
 */

declare(strict_types=1);

namespace Charaka\CurrencyConverter\Model;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Charaka\CurrencyConverter\Model\Config;
use Charaka\CurrencyConverter\Model\Constants;
use Psr\Log\LoggerInterface;

class ApiCall
{

    /**
     * @param Curl $curl
     * @param Json $json
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Curl            $curl,
        private readonly Json            $json,
        private readonly Config          $config,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Get currencies
     *
     * @return array
     */
    public function getCurrencies(): array
    {
        $data = $this->request(Constants::CURRENCIES_ENDPOINT);
        return is_array($data) ? $data : [];
    }

    /**
     * Get latest rates
     *
     * @param string $from
     * @param string $to
     * @return float|null
     */
    public function getLatestRate(string $from, string $to): ?float
    {
        if (empty($from) || empty($to)) {
            return null;
        }

        $conversionRateEndpoint = sprintf(
            Constants::CONVERSION_RATE_ENDPOINT . '/%s/%s',
            rawurlencode(strtolower($from)),
            rawurlencode(strtolower($to))
        );

        $conversionData = $this->request($conversionRateEndpoint);
        if (!is_array($conversionData) || !isset($conversionData[Constants::FIELD_RATE])
            || !is_numeric($conversionData[Constants::FIELD_RATE])
        ) {
            return null;
        }

        return (float)$conversionData[Constants::FIELD_RATE];
    }

    /**
     * Get history rates
     *
     * @param string $from
     * @param string $to
     * @param int $days
     * @return array
     */
    public function getHistoricalRates(string $from, string $to, int $days): array
    {
        $endDate = gmdate('Y-m-d');
        $startDate = gmdate('Y-m-d', strtotime(sprintf('-%d days', $days)));

        $endpoint = sprintf(
            Constants::CONVERSION_RATE_HISTORY_ENDPOINT . '%s&quotes=%s&from=%s&to=%s',
            rawurlencode(strtolower($from)),
            rawurlencode(strtolower($to)),
            $startDate,
            $endDate
        );

        $historyData = $this->request($endpoint);
        if (!is_array($historyData)) {
            return [];
        }

        return array_column($historyData, Constants::FIELD_RATE, Constants::FIELD_DATE);
    }

    /**
     * Api request
     *
     * @param string $endpoint
     * @return array|null
     */
    private function request(string $endpoint): ?array
    {
        $this->log('Requesting Currencies', ['endpoint' => $endpoint]);

        try {
            $this->curl->setTimeout($this->config->getCurlTimeout());
            $this->curl->get($this->config->getApiUrl() . $endpoint);

            if ($this->curl->getStatus() !== 200) {
                $this->log('Requesting Currencies failed', [
                    'endpoint' => $this->config->getApiUrl() . $endpoint,
                    'status_code' => $this->curl->getStatus(),
                    'response' => $this->curl->getBody(),
                ]);
                return null;
            }

            $body = $this->curl->getBody();
            $decodedData = $this->json->unserialize($body);

            $this->log('Requesting Currencies', ['body' => $body]);

            if (is_array($decodedData)) {
                return $decodedData;
            }
        } catch (\Throwable $e) {
            $this->log('Requesting Currencies exception', [
                'endpoint' => $this->config->getApiUrl() . $endpoint,
                'error' => $e->getMessage(),
            ]);
            return null;
        }

        return null;
    }

    /**
     * Log data if enabled
     *
     * @param string $request
     * @param array $data
     * @return void
     */
    private function log(string $request, array $data = []): void
    {
        if ($this->config->logApiCalls()) {
            $this->logger->debug($request, $data);
        }
    }
}
