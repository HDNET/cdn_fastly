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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FastlyService extends AbstractService
{

    /**
     * @var FastlyInterface
     */
    protected $fastly;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function initializeObject(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManager::class);

        // Umstellen auf Extension Konfiguration @todo???
        // OnfigurationService -> Zugriff auf Extension Konfiguration

        $this->setSettings($configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT)['plugin.']['tx_site.']['settings.']['fastly.'] ?? []); // Check site !!!!! @todo
        if (!isset($this->settings['apiKey'])) {
            $message = 'Fastly api key is not available!';
            $this->logger->error($message);
            throw new RuntimeException($message);
        }
        $adapter = $objectManager->get(GuzzleAdapter::class, $this->settings['apiKey']);
        $this->injectFastly($objectManager->get(Fastly::class, $adapter));
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function injectFastly(FastlyInterface $fastly)
    {
        $this->fastly = $fastly;
    }

    /**
     * @param $key
     *
     * @return ResponseInterface
     */
    public function purgeKey($key): ResponseInterface
    {
        try {
            if (!isset($this->settings['serviceId'])) {
                throw new RuntimeException('No service ID');
            }
            $response = $this->fastly->purgeKey($this->settings['serviceId'], $key);
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
            $response = $this->fastly->purgeAll($this->settings['serviceId'], $options);
            $this->logger->notice(\sprintf('FASTLY PURGE ALL: CODE %s', $response->getStatusCode()), (array) $response);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return new HtmlResponse('');
        }

        return $response;
    }
}
