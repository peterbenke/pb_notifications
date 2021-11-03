<?php
namespace PeterBenke\PbNotifications\Backend\ToolbarItems;

/**
 * PbNotifications
 */
use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\StandaloneView;

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
	 * @var ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var NotificationRepository
	 */
	protected $notificationRepository;

	/**
	 * @var string
	 */
	protected $extPath;

	/**
	 * @var ObjectStorage<Notification>
	 */
	protected $notifications;

	/**
	 * @var ObjectStorage<Notification>
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
	public function checkAccess(): bool
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
	}

	/**
	 * Render toolbar icon
	 * @return string
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getItem(): string
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
	public function hasDropDown(): bool
	{
		return true;
	}

	/**
	 * Get the dropdown menu
	 * @return string
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getDropDown(): string
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

		$this->standaloneView->assignMultiple([
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
	public function getAdditionalAttributes(): array
	{
		return [];
	}

	/**
	 * Position relative to others
	 * @return int
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function getIndex(): int
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
	 * @return ResponseInterface
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function renderMenuItem(ServerRequestInterface $request): ResponseInterface
	{
		$response = new HtmlResponse('');
		$response->getBody()->write($this->getItem());
		return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
	}

	/**
	 * Renders the menu as ajax call
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function renderMenu(ServerRequestInterface $request): ResponseInterface
	{
		$response = new HtmlResponse('');
		$response->getBody()->write($this->getDropDown());
		return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
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
	 * @return BackendUserAuthentication|mixed
	 * @author Peter Benke <info@typomotor.de>
	 */
	protected function getBackendUser(): ?BackendUserAuthentication
	{
		return $GLOBALS['BE_USER'];
	}

	/**
	 * @return mixed
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
