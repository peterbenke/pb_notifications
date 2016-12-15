/**
 * Module: TYPO3/CMS/PbNotifications/Toolbar/NotificationsMenu
 * Notifications menu handler
 */
define(['jquery', 'TYPO3/CMS/Backend/Icons'], function($) {
	'use strict';

	var NotificationsMenu = {
		options: {
			containerSelector: '#peterbenke-pbnotifications-backend-toolbaritems-notificationstoolbaritem',
			menuContainerSelector: '.dropdown-menu',
			toolbarIconSelector: '.dropdown-toggle'
		}
	};

	/**
	 * Displays the menu and does the AJAX call to the TYPO3 backend
	 */
	NotificationsMenu.updateMenu = function() {

		// Update the menu item
		$.ajax({
			url: TYPO3.settings.ajaxUrls['pb_notifications_menu_item'],
			type: 'post',
			cache: false,
			success: function(data) {
				$(NotificationsMenu.options.containerSelector).find(NotificationsMenu.options.toolbarIconSelector).html(data);
			}
		});

		// Update the menu
		$.ajax({
			url: TYPO3.settings.ajaxUrls['pb_notifications_menu'],
			type: 'post',
			cache: false,
			success: function(data) {
				$(NotificationsMenu.options.containerSelector).find(NotificationsMenu.options.menuContainerSelector).html(data);
			}
		});

	};

	$(function() {
		NotificationsMenu.updateMenu();
	});

	// expose to global, because we need access from the hook (/Classes/Backend/ToolbarItems/NotificationsToolbarItem.php => updateNumberOfNotificationsHook)
	TYPO3.PbNotificationsMenu = NotificationsMenu;

	return NotificationsMenu;

});