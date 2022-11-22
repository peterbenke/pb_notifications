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

/**
 * Class BackendHook
 * @author Peter Benke <info@typomotor.de>
 */
class BackendHook
{

	/**
	 * Reference back to the backend
	 * @var BackendController
	 */
	protected BackendController $backendReference;

	/**
	 * Show the reminder after login
	 * @param array $config
	 * @param BackendController $backendReference
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function constructPostProcess(array $config, BackendController &$backendReference)
	{

		// Create objects
		/** @var NotificationRepository $notificationRepository */
		$notificationRepository = GeneralUtility::makeInstance(NotificationRepository::class);

		// Only unread notifications
		// $unreadNotifications = $notificationRepository->findOnlyUnreadNotifications();
		$unreadNotifications = $notificationRepository->findOnlyUnreadNotificationsAssignedToUsersUserGroup();

		// We do not need to show a popup to the user after login
		if($unreadNotifications->count() === 0 || !ExtensionConfigurationUtility::forcePopupAfterLogin()){
			return;
		}

		/** @var PageRenderer $pageRenderer */
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
