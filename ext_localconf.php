<?php

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem;

defined('TYPO3') or die();

/*

Procedure for Ajax
================================================================================

See also: https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Backend/Ajax.html

- /Configuration/JavaScriptModules.php
    => register javascript directory 'EXT:pb_notifications/Resources/Public/JavaScript/'

- /Configuration/Backend/AjaxRoutes.php
    => register the routes for ajax 'pb_notifications_menu_item' and 'pb_notifications_menu'
    => used in /Resources/Public/JavaScript/Toolbar/NotificationsMenu.js

- /ext_localconf.php
    => register the hook (NotificationsToolbarItem->updateMenuHook())

- /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php
    => Add the function updateMenuHook() to create the action 'peterbenke:pbnotifications:updateRequested'
    => Add the javascript NotificationsMenu.js in constructor

- /Resources/Public/JavaScript/Toolbar/NotificationsMenu.js
    => Listen to event 'peterbenke:pbnotifications:updateRequested' and call the ajax function

- /Classes/Controller/Backend/NotificationController.php
    => Add the functions renderMenuItem() and renderMenu() to update the menu item and the menu, defined in AjaxRoutes.php


Procedure for Modal after login
================================================================================
See also:
https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Events/Events/Backend/AfterBackendPageRenderEvent.html
https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ApiOverview/Backend/JavaScript/Modules/Modals.html
https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98288-UpdatedBackendModalAPI.html

- /Configuration/Services.yaml
    => register the event listener 'pb-extension/after-backend-page-render'

- /Classes/EventListener/Backend/AfterBackendPageRender.php
    => Register javascript file Reminder.js

- /Resources/Public/JavaScript/Reminder/Reminder.js
    => Add the javascript to create the modal after login

*/

// Register update signal to update the number of notifications
// => /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php->updateMenuHook()
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['updateSignalHook']['PbNotificationsToolbar::updateMenu'] = NotificationsToolbarItem::class . '->updateMenuHook';
