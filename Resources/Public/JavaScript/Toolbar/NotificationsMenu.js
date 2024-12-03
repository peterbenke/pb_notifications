import $ from "jquery";
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

let PbNotificationsMenu = {
    options: {
        // See source code of the backend module for the selectors
        containerSelector: '#peterbenke-pbnotifications-backend-toolbaritems-notificationstoolbaritem',
        menuContainerSelector: '.dropdown-menu',
        toolbarIconSelector: '.dropdown-toggle'
    }
};

class UpdatePbNotificationsMenu {

    /**
     * Update the menu item and the menu.
     * Set in /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php->updateMenuHook().
     * init() called in /Classes/Backend/ToolbarItems/NotificationsToolbarItem.php->__construct().
     */
    init() {
        document.addEventListener("peterbenke:pbnotifications:updateRequested", (() => this.updateMenuItem()));
        document.addEventListener("peterbenke:pbnotifications:updateRequested", (() => this.updateMenu()));
    }

    /**
     * Update the toolbar menu item
     * @see /Configuration/Backend/AjaxRoutes.php: 'pb_notifications_menu_item'
     */
    updateMenuItem = function() {
        new AjaxRequest(TYPO3.settings.ajaxUrls.pb_notifications_menu_item).get().then((async data => {
            $(PbNotificationsMenu.options.containerSelector).find(PbNotificationsMenu.options.toolbarIconSelector).html(await data.resolve());
        }));
    }

    /**
     * Update the toolbar menu
     * @see /Configuration/Backend/AjaxRoutes.php: 'pb_notifications_menu'
     */
    updateMenu = function() {
        new AjaxRequest(TYPO3.settings.ajaxUrls.pb_notifications_menu).get().then((async data => {
            $(PbNotificationsMenu.options.containerSelector).find(PbNotificationsMenu.options.menuContainerSelector).html(await data.resolve());
        }));
    }

}

const pbNotificationsMenuObject = new UpdatePbNotificationsMenu;
"undefined" != typeof TYPO3 && (TYPO3.UpdatePbNotificationsMenu = pbNotificationsMenuObject);
export default pbNotificationsMenuObject;