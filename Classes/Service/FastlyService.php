<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use Fastly\Adapter\Guzzle\GuzzleAdapter;
use Fastly\Fastly;
use Fastly\FastlyInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FastlyService extends AbstractService
{

    /**
     * @var FastlyInterface
     */
    protected $fastly;

    /**
     * @var ConfigurationServiceInterface
     */
    protected $configuration;


    public function injectConfigurationService(ConfigurationServiceInterface $configurationService) {
        $this->configuration = $configurationService;
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function initializeObject(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $adapter = $objectManager->get(GuzzleAdapter::class, $this->configuration->getApiKey());
        $this->fastly = $objectManager->get(FastlyInterface::class, $adapter);
    }

    /**
     * @param $key
     *
     * @return ResponseInterface
     */
    public function purgeKey($key): ResponseInterface
    {
        try {
            $response = $this->fastly->purgeKey($this->configuration->getServiceId(), $key);
            $this->logger->debug(\sprintf('FASTLY PURGE KEY (%s): CODE %s', $key, $response->getStatusCode()), (array) $response);
        } catch (\Exception $exception) {
            $message = 'Fastly service id is not available!';
            $this->logger->error($message);

            return new HtmlResponse('');
        }

        return $response;
    }

    /**
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function purgeAll($options = []): ResponseInterface
    {
        try {
            $response = $this->fastly->purgeAll($this->configuration->getServiceId(), $options);
            $this->logger->notice(\sprintf('FASTLY PURGE ALL: CODE %s', $response->getStatusCode()), (array) $response);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return new HtmlResponse('');
        }

        return $response;
    }
}
