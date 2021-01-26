<?php
namespace PeterBenke\PbNotifications\Hook;

/**
 * PbNotifications
 */
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */
use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class BackendHook
 * @package PeterBenke\PbNotifications\Hook
 * @author Peter Benke <info@typomotor.de>
 */
class BackendHook
{

	/**
	 * Reference back to the backend
	 * @var \TYPO3\CMS\Backend\Controller\BackendController
	 */
	protected $backendReference;

	/**
	 * Show the reminder after login
	 * @param array $config
	 * @param BackendController $backendReference
	 * @author Peter Benke <info@typomotor.de>
	 * @author Sybille Peters <https://github.com/sypets>
	 */
	public function constructPostProcess(array $config, BackendController &$backendReference)
	{

		/**
		 * @var ObjectManager $objectManager
		 * @var PageRenderer $pageRenderer
		 * @var NotificationRepository $notificationRepository
		 */

		// Create objects
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$pageRenderer = $objectManager->get(PageRenderer::class);
		$notificationRepository = $objectManager->get(NotificationRepository::class);

		// Only unread notifications
		// $unreadNotifications = $notificationRepository->findOnlyUnreadNotifications();
		$unreadNotifications = $notificationRepository->findOnlyUnreadNotificationsAssignedToUsersUserGroup();


		// We do not need to show a popup to the user after login
		if($unreadNotifications->count() === 0 || !ExtensionConfigurationUtility::forcePopupAfterLogin()){
			return;
		}

		$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
		$pageRenderer->addInlineLanguageLabelFile('EXT:pb_notifications/Resources/Private/Language/locallang.xlf');
		$pageRenderer->loadRequireJsModule(
			// => pb_notifications/Resources/Public/JavaScript/Reminder/Reminder.js
			'TYPO3/CMS/PbNotifications/Reminder/Reminder',
			'function(reminder){
                reminder.initModal(true);
            }'
		);

	}

}
