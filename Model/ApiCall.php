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
use Psr\Log\LoggerInterface;

class ApiCall
{
    private const string CURRENCIES_ENDPOINT = 'currencies';
    private const string CONVERSION_RATE_ENDPOINT = 'rate';

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
        $data = $this->request(self::CURRENCIES_ENDPOINT);
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
        $conversionRate = 0;
        if (!empty($from) && !empty($to)) {
            $conversionRateEndpoint = sprintf(
                self::CONVERSION_RATE_ENDPOINT . '/%s/%s',
                rawurlencode(strtolower($from)),
                rawurlencode(strtolower($to))
            );
            $conversionData = $this->request($conversionRateEndpoint);
            $conversionRate = $conversionData['rate'];
        }
        return $conversionRate;
    }

    /**
     * Api request
     *
     * @param string $endpoint
     * @return array|null
     */
    private function request(string $endpoint): ?array
    {
        $this->logger->debug('Requesting Currencies', ['endpoint' => $endpoint]);

        try {
            $this->curl->setTimeout($this->config->getCurlTimeout());
            $this->curl->get($this->config->getApiUrl() . $endpoint);

            if ($this->curl->getStatus() !== 200) {
                $this->logger->debug('Requesting Currencies failed', [
                    'endpoint' => $this->config->getApiUrl() . $endpoint,
                    'status_code' => $this->curl->getStatus(),
                    'response' => $this->curl->getBody(),
                ]);
                return null;
            }

            $body = $this->curl->getBody();
            $decodedData = $this->json->unserialize($body);
            $this->logger->debug('Requesting Currencies', ['body' => $body]);

            if (is_array($decodedData)) {
                return $decodedData;
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }
}
