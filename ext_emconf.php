<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CDN Fastly',
    'description' => 'CDN fastly integration for TYPO3 to send the right headers',
    'author' => 'Pavel Musitschenko',
    'author_email' => 'pavel.musitschenko@hdnet.de',
    'state' => 'beta',
    'clearCacheOnLoad' => true,
    'version' => '0.2.0',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-8.1.99',
            'typo3' => '11.5.0-11.5.99',
        ],
    ],
];
