<?php

/**
 * PeterBenke
 */
use PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem;
use PeterBenke\PbNotifications\Hook\BackendHook;

/**
 * TYPO3
 */
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

$boot = static function (): void {

    /** @var Typo3Version $typoVersion */
    $typoVersion = GeneralUtility::makeInstance(Typo3Version::class);
    if ($typoVersion->getMajorVersion() < 12) {

        // Toolbar
        // -----------------------------------------------------------------------------------------------------------------

        /**
         * DONE
         * Should be ok for v12 with autoconfigure in Services.yaml
         *
         * breaking in v12: (remove)
         *  12.0 Breaking: #96041 - Toolbar items: Register by tag
         *
         * Remove $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems']  from your ext_localconf.php file. If autoconfigure is not enabled in your Configuration/Services.(yaml|php), add the tag backend.toolbar.item to your toolbar item class.
         */
        $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1481194871] = NotificationsToolbarItem::class;


        // Reminder after login
        // -----------------------------------------------------------------------------------------------------------------


        /**
         * @todo Add replacement for v12
         *
         * breaking in v12: (remove)
         *  12.0 Breaking: #97451 - Removed BackendController page hooks
         *  12.0 Feature: #97451 - PSR-14 events for modifying backend page content
         *
         * Hook is removed, use event \TYPO3\CMS\Backend\Controller\Event\AfterBackendPageRenderEvent
         */
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['constructPostProcess']['pb_notifications'] = BackendHook::class . '->constructPostProcess';
    }

	// Register icons
	// -----------------------------------------------------------------------------------------------------------------
	/** @var $iconRegistry IconRegistry */
	$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

	$iconRegistry->registerIcon(
		'apps-toolbar-menu-pbnotifications',
		SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/bell-white.svg']
	);

	$iconRegistry->registerIcon(
		'apps-toolbar-menu-pbnotifications-alert',
		SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/bell-orange.svg']
	);

	// Not needed at the moment, folder-icon does not work...? => see also /Configuration/TCA/Overrides/pages.php
	$iconRegistry->registerIcon(
		'apps-pagetree-folder-contains-pb_notifications',
		SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/ext-pb_notifications-folder-tree.svg']
	);

};

$boot();
unset($boot);
