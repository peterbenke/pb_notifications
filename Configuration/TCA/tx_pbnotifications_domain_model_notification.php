<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'title,content,type,marked_as_read,',
        'iconfile' => 'EXT:pb_notifications/Resources/Public/Icons/tx_pbnotifications_domain_model_notification.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, date, type, title, content, images, be_groups, marked_as_read',
    ],
    'types' => [
        '1' => [
            'showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, date, type, title, content;;;richtext:rte_transform[mode=ts_links], images, be_groups, marked_as_read, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'
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
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0,
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
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'date' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date,required',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.title',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required'
            ],
        ],
        'content' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.content',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
                'enableRichtext' => true,

            ],
        ],
        'images' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'minitems' => 0,
                    'maxitems' => 3,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type.information', '0'],
                    ['LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type.warning', '1'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],
        'be_groups' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.be_groups',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_groups',
                'foreign_table_where' => 'ORDER BY be_groups.title',
                'size' => '5',
                'maxitems' => '20',
                'enableMultiSelectFilterTextfield' => true,
            ],
        ],
        'marked_as_read' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.marked_as_read',
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
                'enableMultiSelectFilterTextfield' => true,
            ],
        ],
    ],
];