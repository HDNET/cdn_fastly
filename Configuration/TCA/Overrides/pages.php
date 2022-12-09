<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile('cdn_fastly', 'Configuration/TypoScript/', 'CDN fastly');

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

ExtensionManagementUtility::addToAllTCAtypes('pages', 'fastly');
