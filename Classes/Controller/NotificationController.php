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

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/**
 * Class NotificationController
 * @author Peter Benke <info@typomotor.de>
 */
// since v12 https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ExtensionArchitecture/HowTo/BackendModule/CreateModuleWithExtbase.html
#[AsController]
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

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly PageRenderer $pageRenderer
    ) {
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/PbNotifications/List/notifications');
    }

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
        /**
         * @todo Use dependency injection
         */
		//$this->backendUserRepository = $this->objectManager->get(BackendUserRepository::class);
        $this->backendUserRepository = GeneralUtility::makeInstance(BackendUserRepository::class);
		//$this->persistManager = $this->objectManager->get(PersistenceManager::class);
        $this->persistManager = GeneralUtility::makeInstance(PersistenceManager::class);
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
	public function listAction(): ResponseInterface
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

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());

	}

	/**
	 * Action markAsRead
	 * @throws IllegalObjectTypeException
	 * @throws UnknownObjectException
	 * @throws StopActionException
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function markAsReadAction(): ResponseInterface
	{
		$this->setReadUnread('read');
		return $this->redirect('list');
	}

	/**
	 * Action markAsUnread
	 * @throws IllegalObjectTypeException
	 * @throws StopActionException
	 * @throws UnknownObjectException
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function markAsUnreadAction(): ResponseInterface
	{
		$this->setReadUnread('unread');
		//$this->redirect('list');
        //return $this->htmlResponse();
        return $this->redirect('list');
	}

	/**
	 * Action show
	 * @param Notification $notification
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function showAction(Notification $notification): ResponseInterface
	{
		$this->view->assign('notification', $notification);

        return $this->htmlResponse();
	}

}
