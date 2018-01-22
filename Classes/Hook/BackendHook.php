<?php
namespace PeterBenke\PbNotifications\Hook;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * Class BackendHook
 * @author Peter Benke <info@typomotor.de>
 */
class BackendHook{

	/**
	 * reference back to the backend
	 *
	 * @var \TYPO3\CMS\Backend\Controller\BackendController
	 */
	protected $backendReference;

	/**
	 * Show the reminder after login
	 * @param array $config
	 * @param \TYPO3\CMS\Backend\Controller\BackendController $backendReference
	 */
	public function constructPostProcess(array $config, BackendController &$backendReference) {

		/**
		 * @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
		 * @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
		 * @var \PeterBenke\PbNotifications\Domain\Repository\NotificationRepository $notificationRepository
		 */

		// Create objects
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
		$pageRenderer = $objectManager->get(\TYPO3\CMS\Core\Page\PageRenderer::class);
		$notificationRepository = $objectManager->get(\PeterBenke\PbNotifications\Domain\Repository\NotificationRepository::class);

		// Only unread notifications
		// $unreadNotifications = $notificationRepository->findOnlyUnreadNotifications();
		$unreadNotifications = $notificationRepository->findOnlyUnreadNotificationsAssignedToUsersUserGroup();
		$unreadNotifications->count();

		// Extension configuration for forcing the reminder popup
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pb_notifications']);

		if($unreadNotifications->count() === 0 || $extConf['forceReminderPopUp'] == '0'){
			return;
		}

		$labels = [
			'reminderTitle' => $this->translate('reminder.title'),
			'reminderMessage' => $this->translate('reminder.message'),
		];

		$backendReference->addJavascript('TYPO3.LLL.pbNotifications = ' . json_encode($labels) . ';');

		$pageRenderer->loadRequireJsModule(
			// => pb_notifications/Resources/Public/JavaScript/Reminder/Reminder.js
			'TYPO3/CMS/PbNotifications/Reminder/Reminder',
			'function(reminder){
                reminder.initModal(true);
            }'
		);

	}


	/**
	 * @param string $key
	 * @return null|string
	 */
	protected function translate($key){

		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'pb_notifications');

	}

}
