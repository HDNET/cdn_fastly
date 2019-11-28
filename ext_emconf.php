<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CDN Fastly',
    'description' => 'CDN fastly integration for TYPO3 to send the right headers',
    'author' => 'Pavel Musitschenko',
    'author_email' => 'pavel.musitschenko@hdnet.de',
    'state' => 'beta',
    'clearCacheOnLoad' => true,
    'version' => '0.1.8',
    'constraints' => [
        'depends' => [
            'php' => '7.2.0-7.3.99',
            'typo3' => '9.5.0-9.5.99',
        ],
    ],
];
