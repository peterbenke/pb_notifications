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
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

$boot = static function (): void {

	// Toolbar
	// -----------------------------------------------------------------------------------------------------------------
	$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1481194871] = NotificationsToolbarItem::class;

	// Reminder after login
	// -----------------------------------------------------------------------------------------------------------------
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['constructPostProcess']['pb_notifications'] = BackendHook::class . '->constructPostProcess';

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
