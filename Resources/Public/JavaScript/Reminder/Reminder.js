import Modal from '@typo3/backend/modal.js';
import ModuleMenu from '@typo3/backend/module-menu.js';

class ReminderPbNotifications {

    /**
     * Show the modal to the user, that tg´here are unread notifications
     * init() called in /Classes/EventListener/Backend/AfterBackendPageRender.php->__invoke().
     * @see https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ApiOverview/Backend/JavaScript/Modules/Modals.html
     */
    init(contents) {

        const modal = Modal.advanced({
            title: contents['title'],
            content: contents['content'],
            buttons: [
                {
                    text: 'Ok',
                    name: 'save',
                    icon: 'actions-check',
                    active: true,
                    btnClass: 'btn-primary',
                    trigger: function(event, modal) {
                        modal.hideModal();
                    }
                }
            ]
        });

        modal.addEventListener('button.clicked', (e) => {
            if ((e.target).getAttribute('name') === 'ok') {
                // ...
            }
            modal.hideModal();
            ModuleMenu.App.showModule('user_pb_notificationsNotifications')
        });

    }
}

const reminderPbNotificationsObject = new ReminderPbNotifications;
"undefined" != typeof TYPO3 && (TYPO3.ReminderPbNotifications = reminderPbNotificationsObject);
export default reminderPbNotificationsObject;