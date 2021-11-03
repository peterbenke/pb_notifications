<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pbnotifications_domain_model_notification', 'EXT:pb_notifications/Resources/Private/Language/locallang_csh_tx_pbnotifications_domain_model_notification.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pbnotifications_domain_model_notification');

if (TYPO3_MODE === 'BE') {

    /** @var Typo3Version $tt */
    $tt = GeneralUtility::makeInstance( \TYPO3\CMS\Core\Information\Typo3Version::class ) ;
    if( $tt->getMajorVersion()  < 10 ) {
        // Register the backend module
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'PeterBenke.pb_notifications',
            'user',     // Make module a submodule of 'user'
            'notifications',    // Submodule key
            '',                        // Position
            array(
                'Notification' => 'list, show, markAsRead, markAsUnread',
            ),
            array(
                'access' => 'user,group',
                'icon' => 'EXT:pb_notifications/Resources/Public/Icons/bell-orange.svg',
                'labels' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang.xlf:module.notifications.title',
            )
        );
    } else {
        // Register the backend module
        // see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-87550-UseControllerClassesWhenRegisteringPluginsmodules.html
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'pb_notifications',
            'user',     // Make module a submodule of 'user'
            'notifications',    // Submodule key
            '',                        // Position
            array(
                \PeterBenke\PbNotifications\Controller\NotificationController::class => 'list, show, markAsRead, markAsUnread',
            ),
            array(
                'access' => 'user,group',
                'icon' => 'EXT:pb_notifications/Resources/Public/Icons/bell-orange.svg',
                'labels' => 'LLL:EXT:pb_notifications/Resources/Private/Language/locallang.xlf:module.notifications.title',
            )
        );
    }

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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['updateSignalHook']['PbNotificationsToolbar::updateMenu'] = \PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem::class . '->updateMenuHook';

}

