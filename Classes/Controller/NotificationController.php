<?php
namespace PeterBenke\PbNotifications\Controller;

/**
 * PeterBenke
 */
use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;

/**
 * TYPO3
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/**
 * Class NotificationController
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationController extends ActionController
{

	/**
	 * @var NotificationRepository|null
	 */
	protected ?NotificationRepository $notificationRepository = null;

	/**
	 * @var BackendUserRepository|null
	 */
	protected ?BackendUserRepository $backendUserRepository = null;

	/**
	 * @param NotificationRepository $notificationRepository
	 */
	public function injectNotificationRepository(NotificationRepository $notificationRepository)
	{
		$this->notificationRepository = $notificationRepository;
	}

	/**
	 * @param BackendUserRepository $backendUserRepository
	 */
	public function injectBackendUserRepository(BackendUserRepository $backendUserRepository)
	{
		$this->backendUserRepository = $backendUserRepository;
	}

	/**
	 * @var PersistenceManager|null
	 */
	protected ?PersistenceManager $persistManager = null;


	/**
	 * Common functions
	 * =================================================================================================================
	 */

	/**
	 * Initialize
	 * @author Peter Benke <info@typomotor.de>
	 */
	protected function initializeAction()
	{
		$this->backendUserRepository = $this->objectManager->get(BackendUserRepository::class);
		$this->persistManager = $this->objectManager->get(PersistenceManager::class);
	}

	/**
	 * Sets the notification to read or to unread
	 * @param string $readUnread
	 * @throws IllegalObjectTypeException
	 * @throws UnknownObjectException
	 * @author Peter Benke <info@typomotor.de>
	 */
	private function setReadUnread(string $readUnread)
	{

		/**
		 * @var $notification Notification
		 * @var $beUser BackendUser
		 */

		$beUserId = $GLOBALS['BE_USER']->user['uid'];
		$arguments = $this->request->getArguments();

		$notification = $this->notificationRepository->findByUid(intval($arguments['uid']));
		$beUser = $this->backendUserRepository->findByUid(intval($beUserId));

		if($readUnread == 'read'){
			$notification->addMarkedAsRead($beUser);
		}else{
			$notification->removeMarkedAsRead($beUser);
		}

		$this->notificationRepository->update($notification);
		$this->persistManager->persistAll();

		BackendUtility::setUpdateSignal('PbNotificationsToolbar::updateMenu');

	}

	/**
	 * Actions
	 * =================================================================================================================
	 */

	/**
	 * Action list
	 * @return void
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function listAction()
	{

		// $beUserGroups = $this->getBackendUserGroupsAsArray($GLOBALS['BE_USER']->user['uid']);
		// print_r($GLOBALS['BE_USER']->userGroups);

		$notifications = $this->notificationRepository->findOnlyNotificationsAssignedToUsersUserGroup();
		$this->view->assignMultiple(
			[
				'notifications' => $notifications,
				'user' => $GLOBALS['BE_USER']->user,
			]
		);

	}

	/**
	 * Action markAsRead
	 * @throws IllegalObjectTypeException
	 * @throws UnknownObjectException
	 * @throws StopActionException
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function markAsReadAction()
	{
		$this->setReadUnread('read');
		$this->redirect('list');
	}

	/**
	 * Action markAsUnread
	 * @throws IllegalObjectTypeException
	 * @throws StopActionException
	 * @throws UnknownObjectException
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function markAsUnreadAction()
	{
		$this->setReadUnread('unread');
		$this->redirect('list');
	}

	/**
	 * Action show
	 * @param Notification $notification
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function showAction(Notification $notification)
	{
		$this->view->assign('notification', $notification);
	}

}