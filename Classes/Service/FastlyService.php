<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class FastlyService extends AbstractService
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://api.fastly.com/service/{serviceId}/';

    /**
     * @var ConfigurationServiceInterface
     */
    protected $configuration;

    public function injectConfigurationService(ConfigurationServiceInterface $configurationService): void
    {
        $this->configuration = $configurationService;
    }

    /**
     * Purge single tag from fastly
     *
     * @param string $key
     */
    public function purgeKey(string $key): void
    {
        try {
            $this->getClient()->request('POST', 'purge/' . $key);
            if ($this->logger) {
                $this->logger->debug(\sprintf('FASTLY PURGE KEY (%s)', $key));
            }
        } catch (\Exception $exception) {
            if ($this->logger) {
                $message = 'Fastly service id is not available!';
                $this->logger->error($message);
            }
        }
    }

    /**
     * Pruge multiple tags from CDN
     *
     * @param array<string> $keys
     */
    public function purgeKeys(array $keys): void
    {
        if (empty($keys)) {
            return;
        }
        try {
            $this->getClient()->request('POST', 'purge/', [
                'headers' => [
                    'Surrogate-Key' => implode(' ', $keys),
                ],
            ]);
            if ($this->logger) {
                $this->logger->debug(\sprintf('FASTLY PURGE KEYS (%s)', implode(' ', $keys)));
            }
        } catch (\Exception $exception) {
            if ($this->logger) {
                $message = 'Fastly service id is not available!';
                $this->logger->error($message);
            }
        }
    }

    /**
     * Purge all cached objects from Fastly
     */
    public function purgeAll(): void
    {
        try {
            $this->getClient()->post('purge_all');
            if ($this->logger) {
                $this->logger->notice(\sprintf('FASTLY PURGE ALL:'));
            }
        } catch (\Exception $exception) {
            if ($this->logger) {
                $this->logger->error($exception->getMessage());
            }
        }
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->initializeClient($this->configuration->getServiceId(), $this->configuration->getApiKey());
    }

    /**
     * @param string $serviceId
     * @param string $apiToken
     *
     * @return Client
     */
    protected function initializeClient(string $serviceId, string $apiToken)
    {
        $httpOptions = $GLOBALS['TYPO3_CONF_VARS']['HTTP'];
        if (isset($httpOptions['handler'])) {
            if (is_array($httpOptions['handler'] && !empty($httpOptions['handler']))) {
                $stack = HandlerStack::create();
                foreach ($httpOptions['handler'] as $handler) {
                    $stack->push($handler);
                }
                $httpOptions['handler'] = $stack;
            } else {
                unset($httpOptions['handler']);
            }
        }
        $httpOptions['verify'] = filter_var($httpOptions['verify'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $httpOptions['verify'];
        $httpOptions['base_uri'] = str_replace('{serviceId}', $serviceId, $this->baseUrl);
        $httpOptions['headers']['Fastly-Key'] = $apiToken;
        $httpOptions['headers']['Fastly-Soft-Purge'] = 1;

        return new Client($httpOptions);
    }
}
