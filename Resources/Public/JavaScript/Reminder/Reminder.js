/**
 * Reminder for the notifications
 *
 * Changes for TYPO3 v12:
 *   Breaking: #98288 - Updated Backend Modal API
 *   https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98288-UpdatedBackendModalAPI.html
 */
define(['jquery', 'TYPO3/CMS/Backend/Modal'], function ($, Modal) {

    'use strict';

    var Reminder = {};

    Reminder.initModal = function (force) {

        $(function () {

          const modal = Modal.show(
            TYPO3.lang['reminder.title'],
            TYPO3.lang['reminder.message'],
            TYPO3.Severity.warning,
            [
              {
                text: TYPO3.lang['button.ok'] || 'OK',
                btnClass: 'btn-warning',
                name: 'ok',
                active: true,
                trigger: function(event, modal) {
                  modal.hideModal();
                }
              }
              ]
          );

          modal.addEventListener('hidden.bs.modal', function() {
            console.log('pb_notifications: Reminder: in Modal: hidden.bs.modal');
            /**
             * @todo go to module user_PbNotificationsNotifications
             *
             * TYPO3 v12 top.gotToModule is deprecated in v11 and removed in v12
             * Deprecation: #94058 - JavaScript goToModule()
             * https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94058-JavaScriptGoToModule.html
             * Use the following HTML code to replace the inline goToModule() call to for example link to the page module:
             *
             * <a href="#"
             *   data-dispatch-action="TYPO3.ModuleMenu.showModule"
             *   data-dispatch-args-list="web_layout"
             *   >
             *   Go to page module
             *   </a>
             */
             //top.goToModule('user_PbNotificationsNotifications');
         });

        });
    };

  return Reminder;

});
