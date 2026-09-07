<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;

class FastlyService extends AbstractService
{
    protected string $baseUrl = 'https://api.fastly.com/service/{serviceId}/';

    public function __construct(private readonly ConfigurationServiceInterface $configuration)
    {
    }

    protected function getClient(): Client
    {
        $serviceId = $this->configuration->getServiceId();
        $apiToken = $this->configuration->getApiKey();
        $httpOptions = $GLOBALS['TYPO3_CONF_VARS']['HTTP'];
        if (isset($httpOptions['handler'])) {
            if (is_array(!empty($httpOptions['handler']))) {
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
        $httpOptions['timeout'] = 10.0; // 10 seconds
        $httpOptions['base_uri'] = str_replace('{serviceId}', $serviceId, $this->baseUrl);
        $httpOptions['headers']['Fastly-Key'] = $apiToken;
        if ($this->configuration->getSoftpurge()) {
            $httpOptions['headers']['Fastly-Soft-Purge'] = 1;
        }

        return new Client($httpOptions);
    }

    public function purgeKey(string $key): void
    {
        try {
            $this->getClient()->request('POST', 'purge/' . $key);
            $this->logger?->debug(\sprintf('FASTLY PURGE KEY (%s)', $key));
        } catch (Exception|GuzzleException $exception) {
            $this->logger?->error(
                \sprintf('FASTLY PURGE KEY (%s) failed: %s', $key, $exception->getMessage()),
                ['exception' => $exception]
            );
        }
    }

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
            $this->logger?->debug(\sprintf('FASTLY PURGE KEYS (%s)', implode(' ', $keys)));
        } catch (Exception|GuzzleException $exception) {
            $this->logger?->error(
                \sprintf('FASTLY PURGE KEYS (%s) failed: %s', implode(' ', $keys), $exception->getMessage()),
                ['exception' => $exception]
            );
        }
    }

    public function purgeAll(): void
    {
        try {
            $this->getClient()->post('purge_all');
            $this->logger?->notice('FASTLY PURGE ALL');
        } catch (Exception|GuzzleException $exception) {
            $this->logger?->error(
                \sprintf('FASTLY PURGE ALL failed: %s', $exception->getMessage()),
                ['exception' => $exception]
            );
        }
    }
}
