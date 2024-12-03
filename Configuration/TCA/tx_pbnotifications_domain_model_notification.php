<?php

$ll = 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:';

$tx_pb_notifications_domain_model_notification = [
    'ctrl' => [
        'title'	=> $ll . 'tx_pbnotifications_domain_model_notification',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
		],
        'searchFields' => 'title,content,type,marked_as_read,',
        'iconfile' => 'EXT:pb_notifications/Resources/Public/Icons/tx_pbnotifications_domain_model_notification.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => 'sys_language_uid,--palette--,l10n_parent,l10n_diffsource,hidden,--palette--;;1,date,type,title,content,--palette--,images,be_groups,marked_as_read,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => ''
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'tx_pbnotifications_domain_model_notification',
                'foreign_table_where' => 'AND tx_pbnotifications_domain_model_notification.pid=###CURRENT_PID### AND tx_pbnotifications_domain_model_notification.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                ['behaviour' => ['allowLanguageSynchronization' => true]],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                ['behaviour' => ['allowLanguageSynchronization' => true]],
            ],
        ],
        'date' => [
            'exclude' => 0,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.date',
            'config' => [
                'type' => 'datetime',
                'size' => 7,
                'checkbox' => 1,
                'default' => time(),
                'format' => 'date',
                'required' => true
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.title',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'content' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.content',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
                'required' => true,

            ],
        ],
        'images' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.images',
            'config' => [
                ### !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                'minitems' => 0,
                'maxitems' => 3,
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => $ll . 'tx_pbnotifications_domain_model_notification.type.information', 'value' => '0'],
                    ['label' => $ll . 'tx_pbnotifications_domain_model_notification.type.warning', 'value' => '1'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'required' => true,
            ],
        ],
        'be_groups' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.be_groups',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_groups',
                'foreign_table_where' => 'ORDER BY be_groups.title',
                'size' => '5',
                'maxitems' => '20',
            ],
        ],
        'marked_as_read' => [
            'exclude' => 1,
            'label' => $ll . 'tx_pbnotifications_domain_model_notification.marked_as_read',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'AND 1=1 ORDER BY username ASC',
                'MM' => 'tx_pbnotifications_notification_backenduser_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],
    ],
];
return $tx_pb_notifications_domain_model_notification;