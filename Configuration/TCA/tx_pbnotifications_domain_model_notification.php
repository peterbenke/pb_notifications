<?php
return array(
	'ctrl' => array(
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
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('pb_notifications') . 'Resources/Public/Icons/tx_pbnotifications_domain_model_notification.svg'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, date, type, title, content, images, be_groups, marked_as_read',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, date, type, title, content;;;richtext:rte_transform[mode=ts_links], images, be_groups, marked_as_read, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_pbnotifications_domain_model_notification',
				'foreign_table_where' => 'AND tx_pbnotifications_domain_model_notification.pid=###CURRENT_PID### AND tx_pbnotifications_domain_model_notification.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'date' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.date',
			'config' => array(
				'type' => 'input',
				'size' => 10,
				'max' => 20,
				'eval' => 'date,required',
			),
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.title',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim,required'
			),
		),
		'content' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim,required',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'module' => array(
							'name' => 'wizard_rich_text_editor',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'wizard_rte.php'
							)
						),
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
		),
		'images' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('images', array(
				'appearance' => array(
					'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
				),
				'minitems' => 0,
				'maxitems' => 3,
			), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
		),
		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type.information', '0'),
					array('LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.type.warning', '1'),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required',
			),
		),
		'be_groups' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.be_groups',

			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'be_groups',
				'foreign_table_where' => 'ORDER BY be_groups.title',
				'size' => '5',
				'maxitems' => '20',
				'enableMultiSelectFilterTextfield' => true,
			),


		),
		'marked_as_read' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang_db.xlf:tx_pbnotifications_domain_model_notification.marked_as_read',
			'config' => array(
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
				/*
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'module' => array(
							'name' => 'wizard_edit',
						),
						'type' => 'popup',
						'title' => 'Edit',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'module' => array(
							'name' => 'wizard_add',
						),
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'be_users',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
					),
				),
				*/
			),
		),
		
	),
);