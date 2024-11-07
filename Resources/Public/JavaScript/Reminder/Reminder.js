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
            TYPO3.ModuleMenu.App.showModule('user_PbNotificationsNotifications');
         });

        });
    };

  return Reminder;

});
