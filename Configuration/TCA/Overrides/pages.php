<?php

declare(strict_types=1);

use HDNET\Autoloader\Utility\ModelUtility;
use HDNET\Site\Service\TcaService;

$GLOBALS['TCA']['pages'] = ModelUtility::getTcaOverrideInformation('cdn_fastly', 'pages');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'cdn_fastly',
    'Resources/Private/TsConfig/Main/Root.ts',
    'T3M Main'
);

$GLOBALS['TCA']['pages']['columns']['fastly'] = [
    'exclude' => 1,
    'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:pages.fastly',
    'config' => [
        'type' => 'check',
        'renderType' => 'checkboxToggle',
        'items' => [
            [
                0 => false,
                1 => true,
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'social_menu_icon,menu_cols,fastly');
