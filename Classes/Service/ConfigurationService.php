<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Service;

use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class ConfigurationService implements ConfigurationServiceInterface
{
    public function getApiKey(): string
    {
        $config = $this->findConfiguration();
        $this->validArrayProperty($config, 'apiKey');

        return $config['apiKey'];
    }

    protected function findConfiguration(): array
    {
        static $foundConfig;

        if ($foundConfig === null) {
            $foundConfig = \array_merge($this->findTypoScriptConfiguration(), $this->findGlobalConfiguration());
        }

        $checkEnvs = ['apiKey', 'serviceId'];
        foreach ($checkEnvs as $value) {
            if (isset($foundConfig[$value]) && \is_string($foundConfig[$value]) && \str_starts_with($foundConfig[$value], 'env:')) {
                $foundConfig[$value] = \getenv(\mb_substr($foundConfig[$value], 4));
            }
        }

        return $foundConfig;
    }

    protected function validArrayProperty(array $config, string $property): void
    {
        if (!\is_string($config[$property]) || empty($config[$property])) {
            throw new RuntimeException('No or invalid property: ' . $property);
        }
    }

    protected function findTypoScriptConfiguration(): array
    {
        try {
            $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
            $typoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        } catch (\Exception) {
            return [];
        }
        return (array)($typoScript['plugin.']['tx_cdnfastly.']['settings.'] ?? []);
    }

    protected function findGlobalConfiguration(): array
    {
        $config = (array)($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['cdn_fastly'] ?? []);
        return \array_filter($config, static fn($value): bool => $value !== '' && $value !== null);
    }

    public function getServiceId(): string
    {
        $config = $this->findConfiguration();
        $this->validArrayProperty($config, 'serviceId');

        return $config['serviceId'];
    }

    public function getSoftpurge(): bool
    {
        $config = $this->findConfiguration();
        return (bool)$config['softpurge'];
    }
}
