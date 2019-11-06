<?php
/**
 *
 */


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

    protected function validArrayProperty(array $config, string $property)
    {
        if (!isset($config[$property]) || !is_string($config[$property]) || empty($config[$property])) {
            throw new RuntimeException('No or invalid property: ' . $property);
        }
    }


    protected function findConfiguration(): array
    {
        static $foundConfig;

        if ($foundConfig === null) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $configurationManager = $objectManager->get(ConfigurationManager::class);
            $foundConfig = (array)($configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT)['plugin.']['tx_site.']['settings.']['fastly.'] ?? []);
        }

        return $foundConfig;
    }
}
