<?php


return [
    'ctrl' => [
        'title' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:title',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'endtime' => 'endtime',
            'disabled' => 'hidden',
            'starttime' => 'starttime',
        ],
        'iconfile' => 'EXT:cdn_fastly/Resources/Public/Icons/Extension.png'
    ],
    'types' => [
        '1' => [
            'showitem' => '--div--;LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:general, hidden, title, username, password, --div--;LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:languages,language',
        ],
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:hidden',
            'exclude' => 1,
            'config' => [
                'type' => 'check'
            ]
        ],
        'title' => [
            'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:logintitle',
            'config' => [
                'type' => 'input',
                'eval' => 'required'
            ],
        ],
        'username' => [
            'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:username',
            'config' => [
                'type' => 'input',
                'eval' => 'required'
            ],
        ],
        'password' => [
            'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:password',
            'config' => [
                'type' => 'input',
                'eval' => 'password, required'
            ],
        ],
        'language' => [
            'label' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:language',
            'config' => [
                'type' => 'select',
                'default' => '',
                'special' => 'languages',
                'renderType' => 'selectCheckBox',
            ],
        ],

    ],
];
