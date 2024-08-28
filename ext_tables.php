<?php

/**
 * PeterBenke
 */
use PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem;

/**
 * TYPO3
 */

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;


defined('TYPO3') or die();

$boot = static function (): void {

    /**
     * @todo: addLLrefForTCAdescr is deprecated in v12
     *   Deprecation: #97312 - Deprecate CSH-related methods
     */
    ExtensionManagementUtility::addLLrefForTCAdescr('tx_pbnotifications_domain_model_notification', 'EXT:pb_notifications/Resources/Private/Language/locallang_csh_tx_pbnotifications_domain_model_notification.xlf');

    /**
     * @todo Replace once support for v11 is dropped
     *
     * deprecated in v12: The API method :php:`ExtensionManagementUtility::allowTableOnStandardPages` which
     * was used in `ext_tables.php` files of extensions registering custom records available
     * on any page type has been marked as deprecated.
     *
     * see
     *  12.0 Breaking: #98487 - $GLOBALS['PAGES_TYPES'] removed
     *  12.0 Deprecation: #98487 - ExtensionManagementUtility::allowTableOnStandardPages
     *  12.0 Feature: #98487 - TCA option [ctrl][security][ignorePageTypeRestriction]
     */
    ExtensionManagementUtility::allowTableOnStandardPages('tx_pbnotifications_domain_model_notification');

    /** @var Typo3Version $typoVersion */
    $typoVersion = GeneralUtility::makeInstance(Typo3Version::class);

    if ($typoVersion->getMajorVersion() < 12) {

        /**
         * DONE (see Configuration/Modules.php for v12)
         *
         * Register the backend module
         * breaking in v12 (remove):
         *   12.0  Breaking: #96733 - Removed support for module handling based on TBE_MODULES
         */
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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['updateSignalHook']['PbNotificationsToolbar::updateMenu'] = NotificationsToolbarItem::class . '->updateMenuHook';

};

$boot();
unset($boot);



