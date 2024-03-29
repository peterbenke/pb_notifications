<?php

/**
 * PeterBenke
 */
use PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem;

/**
 * TYPO3
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;


defined('TYPO3') or die();

$boot = static function (): void {

	ExtensionManagementUtility::addLLrefForTCAdescr('tx_pbnotifications_domain_model_notification', 'EXT:pb_notifications/Resources/Private/Language/locallang_csh_tx_pbnotifications_domain_model_notification.xlf');
	ExtensionManagementUtility::allowTableOnStandardPages('tx_pbnotifications_domain_model_notification');

	// Register the backend module
	ExtensionUtility::registerModule(
		'pb_notifications',
		'user',     // Make module a submodule of 'user'
		'notifications',    // Submodule key
		'',                        // Position
		[
			\PeterBenke\PbNotifications\Controller\NotificationController::class => 'list, show, markAsRead, markAsUnread',
		],
		[
			'access' => 'user,group',
			'icon' => 'EXT:pb_notifications/Resources/Public/Icons/bell-orange.svg',
			'labels' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang.xlf:module.notifications.title',
		]
	);


	/*

	Procedure:
	================================================================================

	Embedding Javascript
	--------------------------------------------------------------------------------
	- /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php
		=> $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/PbNotifications/Toolbar/NotificationsMenu');
		=> pay attention to the path (inside TYPO3/CMS, CamelCase Extension), must! be inside /Resources/Public/JavaScript/
		=> File is embedded by require.js: /Resources/Public/JavaScript/Toolbar/NotificationsMenu.js

		Example for js: /typo3/sysext/opendocs/Resources/Public/JavaScript/Toolbar/OpendocsMenu.js

	Define the hook, see below
	--------------------------------------------------------------------------------
	- /ext_tables.php

	Prepare ajax
	--------------------------------------------------------------------------------
	- /Configuration/Backend/AjaxRoutes.php
	- /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php => renderMenuItem / renderMenu

	In the controller, call the hook
	--------------------------------------------------------------------------------
	- /Classes/Controller/NotificationController.php: BackendUtility::setUpdateSignal('PbNotificationsToolbar::updateMenu');
		=> /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php => updateMenuHook
		=> js-snippet will be rendered
		=> javascript above will be executed
		=> ajax will be called
		=> menu will be updated

	*/

	// Register update signals to update the number of unread notifications in the toolbar, see also /Classes/Controller/NotificationController.php
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['updateSignalHook']['PbNotificationsToolbar::updateMenu'] = NotificationsToolbarItem::class . '->updateMenuHook';

};

$boot();
unset($boot);



