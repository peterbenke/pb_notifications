/**
 * Reminder for the notifications
 */
define(['jquery', 'TYPO3/CMS/Backend/Modal'], function ($, Modal) {

    'use strict';

    var Reminder = {};

    Reminder.initModal = function (force) {

        $(function () {
			Modal.show(
				TYPO3.lang['reminder.title'],
				TYPO3.lang['reminder.message'],
				TYPO3.Severity.warning,
				[{
					text: TYPO3.lang['button.ok'] || 'OK',
					btnClass: 'btn-warning',
					name: 'ok',
					active: true
				}]
			).on('button.clicked', function () {
				Modal.currentModal.trigger('modal-dismiss');
			}).on('hidden.bs.modal', function () {
				top.goToModule('user_PbNotificationsNotifications');
			});
        });
    };

    return Reminder;

});
