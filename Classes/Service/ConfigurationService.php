<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ConfigurationService implements ConfigurationServiceInterface
{
    public function getApiKey(): string
    {
        $config = $this->findConfiguration();
        $this->validArrayProperty($config, 'apiKey');

        return $config['apiKey'];
    }

    public function getServiceId(): string
    {
        $config = $this->findConfiguration();
        $this->validArrayProperty($config, 'serviceId');

        return $config['serviceId'];
    }

    /**
     * @param array<mixed> $config
     * @param string $property
     */
    protected function validArrayProperty(array $config, string $property): void
    {
        if (!isset($config[$property]) || !\is_string($config[$property]) || empty($config[$property])) {
            throw new RuntimeException('No or invalid property: ' . $property);
        }
    }

    /**
     * @return array<mixed>
     */
    protected function findConfiguration(): array
    {
        static $foundConfig;

        if ($foundConfig === null) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $foundConfig = (array)($configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT)['plugin.']['tx_cdnfastly.']['settings.'] ?? []);
        }

        $checkEnvs = ['apiKey', 'serviceId'];
        foreach ($checkEnvs as $value) {
            if (isset($foundConfig[$value]) && \is_string($foundConfig[$value]) && \str_starts_with($foundConfig[$value], 'env:')) {
                $foundConfig[$value] = \getenv(\mb_substr($foundConfig[$value], 4));
            }
        }

        return $foundConfig;
    }
}
