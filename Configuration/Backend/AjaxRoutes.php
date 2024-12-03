<?php

use PeterBenke\PbNotifications\Controller\Backend\NotificationController;

return [

	// Render the toolbar menu item
	'pb_notifications_menu_item' => [
		'path' => '/pb_notifications/menu_item',
		'target' => NotificationController::class . '::renderMenuItem'
	],

    // Render the toolbar dropdown menu
    'pb_notifications_menu' => [
        'path' => '/pb_notifications/menu',
        'target' => NotificationController::class . '::renderMenu'
    ],

];
