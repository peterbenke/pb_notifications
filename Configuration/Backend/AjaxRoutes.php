<?php
return [

	// Render the toolbar menu item
	'pb_notifications_menu_item' => [
		'path' => '/pb_notifications/menu_item',
		'target' => \PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem::class . '::renderMenuItem'
	],

    // Render the toolbar dropdown menu
    'pb_notifications_menu' => [
        'path' => '/pb_notifications/menu',
        'target' => \PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem::class . '::renderMenu'
    ],

];
