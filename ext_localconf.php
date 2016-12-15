<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {

	// Toolbar
	// -------------------------------------------------------------------------------------------------------------------------------------
	$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1481194871] = \PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem::class;

	// Register icons
	// -------------------------------------------------------------------------------------------------------------------------------------
	/** @var $iconRegistry \TYPO3\CMS\Core\Imaging\IconRegistry */
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

	$iconRegistry->registerIcon(
		'apps-toolbar-menu-pbnotifications',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/bell-white.svg']
	);

	$iconRegistry->registerIcon(
		'apps-toolbar-menu-pbnotifications-alert',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/bell-orange.svg']
	);

	// Not needed at the moment, folder-icon does not work...? => see also /Configuration/TCA/Overrides/pages.php
	$iconRegistry->registerIcon(
		'apps-pagetree-folder-contains-pb_notifications',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:pb_notifications/Resources/Public/Icons/ext-pb_notifications-folder-tree.svg']
	);

}
