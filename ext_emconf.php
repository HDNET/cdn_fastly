<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CDN Fastly',
    'description' => 'CDN fastly integration for TYPO3 to send the right headers',
    'author' => 'Pavel Musitschenko',
    'author_email' => 'pavel.musitschenko@hdnet.de',
    'state' => 'beta',
    'version' => '0.3.0',
    'constraints' => [
        'depends' => [
            'php' => '8.4.0-8.4.16',
            'typo3' => '13.4.0-13.4.99',
        ],
    ],
];
