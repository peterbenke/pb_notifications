<?php
namespace PeterBenke\PbNotifications\Backend\ToolbarItems;

/**
 * PbNotifications
 */
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Psr
 */
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Class NotificationsToolbarItem
 * @package PeterBenke\PbNotifications\Backend\ToolbarItems
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationsToolbarItem implements ToolbarItemInterface
{

	/**
	 * @var StandaloneView
	 */
	protected $standaloneView = null;

	/**
	 * @var IconFactory
	 */
	protected $iconFactory;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;

	/**
	 * notificationRepository
	 *
	 * @var \PeterBenke\PbNotifications\Domain\Repository\NotificationRepository
	 */
	protected $notificationRepository;

	/**
	 * @var string
	 */
	protected $extPath;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
	 */
	protected $notifications;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
	 */
	protected $onlyUnreadNotifications;

	/**
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function __construct()
	{

		$this->extPath = ExtensionManagementUtility::extPath('pb_notifications');

		// StandaloneView
		$this->standaloneView = GeneralUtility::makeInstance(StandaloneView::class);

		// Load the javascript
		$this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/PbNotifications/Toolbar/NotificationsMenu');

		// Icons
		$this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);

		// Locallang
		$this->getLanguageService()->includeLLFile('EXT:pb_notifications/Resources/Private/Language/locallang.xlf');

		// Repository
		$this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$this->notificationRepository = $this->objectManager->get(NotificationRepository::class);

		// All Notifications
		// $this->notifications = $this->notificationRepository->findAll();
		$this->notifications = $this->notificationRepository->findOnlyNotificationsAssignedToUsersUserGroup();

		// Only unread notifications
		$this->onlyUnreadNotifications = $this->notificationRepository->findOnlyUnreadNotifications();

	}

	/**
	 * Checks whether the user has access to this toolbar item
	 * @return bool TRUE if user has access, FALSE if not
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function checkAccess()
	{

		$beUser = $this->getBackendUser();
		if (
			$beUser->isAdmin()
			||
			GeneralUtility::inList($beUser->groupData['modules'], 'user_PbNotificationsNotifications')
		) {
			return true;
		}
		return false;

		// $conf = $this->getBackendUser()->getTSConfig('backendToolbarItem.tx_pbnotifications.disabled');
		// return $conf['value'] != 1;
	}

	/**
	 * Render toolbar icon
	 * @return string
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getItem()
	{

		if (!$this->checkAccess()) {
			return '';
		}

		$this->standaloneView->setTemplatePathAndFilename($this->extPath . 'Resources/Private/Templates/ToolbarMenu/MenuItem.html');

		try{
			$request = $this->standaloneView->getRequest();
			$request->setControllerExtensionName('pb_notifications');
		}catch(InvalidExtensionNameException $e){
			return $e->getMessage();
		}

		$this->standaloneView->assign('notifications', $this->notifications);
		$this->standaloneView->assignMultiple([
			'notifications' => $this->notifications
		]);

		return $this->standaloneView->render();

	}

	/**
	 * This item has no drop down
	 * @return bool
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function hasDropDown()
	{
		return true;
	}

	/**
	 * Get the drop down menu
	 * @return string
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getDropDown()
	{

		if (!$this->checkAccess()) {
			return '';
		}

		$this->standaloneView->setTemplatePathAndFilename($this->extPath . 'Resources/Private/Templates/ToolbarMenu/DropDown.html');

		try{
			$request = $this->standaloneView->getRequest();
			$request->setControllerExtensionName('pb_notifications');
		}catch(InvalidExtensionNameException $e){
			return $e->getMessage();
		}

		$maxNumberOfNotificationsInToolbar = ExtensionConfigurationUtility::getMaxNumberOfNotificationsInToolbar();
		if(!intval($maxNumberOfNotificationsInToolbar) > 0){
			$maxNumberOfNotificationsInToolbar = 1000;
		}

		/** @var UriBuilder $uriBuilder */
		/*
		$notificationListUrl = null;
		try{
			$uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
			$notificationListUrl = $uriBuilder->buildUriFromRoute('user_PbNotificationsNotifications');
		}catch(RouteNotFoundException $e){
			return $e->getMessage();
		}
		*/

		$this->standaloneView->assignMultiple([
//			't3Version' => substr(VersionNumberUtility::getNumericTypo3Version(), 0, 1)
//			'notificationListUrl' => $notificationListUrl,
			'onlyUnreadNotifications' => $this->onlyUnreadNotifications,
			'maxNumberOfNotificationsInToolbar' => $maxNumberOfNotificationsInToolbar,
		]);

		return $this->standaloneView->render();

	}

	/**
	 * No additional attributes
	 * @return array List item HTML attributes
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getAdditionalAttributes()
	{
		return [];
	}

	/**
	 * Position relative to others
	 * @return int
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getIndex()
	{
		return 30;
	}

	/**
	 * Other functions (not obliged in Interface)
	 * =================================================================================================================
	 */

	/**
	 * Ajax
	 * = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	 */

	/**
	 * Renders the menuItem as ajax call
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function renderMenuItem(ServerRequestInterface $request, ResponseInterface $response)
	{
		unset($request); // Avoid IDE warning
		$response->getBody()->write($this->getItem());
		$response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
		return $response;
	}


	/**
	 * Renders the menu as ajax call
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function renderMenu(ServerRequestInterface $request, ResponseInterface $response)
	{
		unset($request); // Avoid IDE warning
		$response->getBody()->write($this->getDropDown());
		$response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
		return $response;
	}

	/**
	 * Hooks
	 * = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	 */

	/**
	 * Called as a hook in \TYPO3\CMS\Backend\Utility\BackendUtility::setUpdateSignal (NotificationController)
	 * Updates the notification menu
	 * @param array $params
	 * @param $ref
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function updateMenuHook(array &$params, $ref)
	{
		unset($ref); // Avoid IDE warning
		$params['JScode'] = '
			if (top && top.TYPO3.PbNotificationsMenu) {
				top.TYPO3.PbNotificationsMenu.updateMenu();
			}
		';
	}

	/**
	 * Miscellaneous
	 * = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	 *

	/**
	 * Returns the current BE user
	 * @return BackendUserAuthentication
	 * @author Peter Benke <info@typomotor.de>
	 */
	protected function getBackendUser()
	{
		return $GLOBALS['BE_USER'];
	}

	/**
	 * Returns LanguageService
	 * @return LanguageService
	 * @author Peter Benke <info@typomotor.de>
	 */
	protected function getLanguageService()
	{
		return $GLOBALS['LANG'];
	}

	/**
	 * Returns current PageRenderer
	 * @return PageRenderer|LoggerAwareInterface|SingletonInterface
	 * @author Peter Benke <info@typomotor.de>
	 */
	protected function getPageRenderer()
	{
		return GeneralUtility::makeInstance(PageRenderer::class);
	}

}
