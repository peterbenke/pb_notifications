<?php

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

/**
 * IMPORTANT note:
 * If you add new icons and nothing happens, clear also the browser cache,
 * because javascript for backend might be dynamically generated.
 * =====================================================================================================================
 */

$icons = [
    'pb-notifications-module' => 'pb-notifications-module.svg',
    'apps-pagetree-folder-contains-pb_notifications' => 'ext-pb_notifications-folder-tree.svg',
    'pb-notifications-toolbar-menu' => 'bell-white.svg',
    'pb-notifications-toolbar-menu-alert' => 'bell-orange.svg',
];

// Register icons
$iconList = [];
foreach ($icons as $identifier => $path) {
    $iconList[$identifier] = [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pb_notifications/Resources/Public/Icons/' . $path,
    ];
}

unset($icons);
return $iconList;