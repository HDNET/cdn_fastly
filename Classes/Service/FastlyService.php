<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

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

    public function injectConfigurationService(ConfigurationServiceInterface $configurationService)
    {
        $this->configuration = $configurationService;
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function initializeObject(): void
    {
    }

    public function purgeKey(string $key): void
    {
        try {
            $this->getClient()->request('POST', 'purge/'.$key);
            $this->logger->debug(\sprintf('FASTLY PURGE KEY (%s)', $key));
        } catch (\Exception $exception) {
            $message = 'Fastly service id is not available!';
            $this->logger->error($message);
        }
    }

    public function purgeAll(): void
    {
        try {
            $this->getClient()->post('purge_all');
            $this->logger->notice(\sprintf('FASTLY PURGE ALL:'));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
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
     * @param $serviceId
     * @param $apiToken
     *
     * @return Client
     */
    protected function initializeClient($serviceId, $apiToken)
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

        return new Client($httpOptions);
    }
}
