<?php
namespace PeterBenke\PbNotifications\Backend\ToolbarItems;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Peter Benke <info@typomotor.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;

use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Fluid\View\StandaloneView;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * A list of all notifications
 *
 */
class NotificationsToolbarItem implements ToolbarItemInterface{

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
	 * NotificationsToolbarItem constructor
	 */
	public function __construct(){

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
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->notificationRepository = $this->objectManager->get('PeterBenke\\PbNotifications\\Domain\\Repository\\NotificationRepository');

		// All Notifications
		// $this->notifications = $this->notificationRepository->findAll();
		$this->notifications = $this->notificationRepository->findOnlyNotificationsAssignedToUsersUserGroup();

		// Only unread notifications
		$this->onlyUnreadNotifications = $this->notificationRepository->findOnlyUnreadNotifications();

	}

	/**
	 * Checks whether the user has access to this toolbar item
	 *
	 * @return bool TRUE if user has access, FALSE if not
	 */
	public function checkAccess(){

		$beUser = $this->getBackendUser();
		if (
			$beUser->isAdmin()
			||
			\TYPO3\CMS\Core\Utility\GeneralUtility::inList($beUser->groupData['modules'], 'user_PbNotificationsNotifications')
		) {
			return true;
		}
		return false;

		// $conf = $this->getBackendUser()->getTSConfig('backendToolbarItem.tx_pbnotifications.disabled');
		// return $conf['value'] != 1;
	}

	/**
	 * Render toolbar icon
	 *
	 * @return string HTML
	 */
	public function getItem(){

		if (!$this->checkAccess()) {
			return '';
		}

		$this->standaloneView->setTemplatePathAndFilename($this->extPath . 'Resources/Private/Templates/ToolbarMenu/MenuItem.html');

		$request = $this->standaloneView->getRequest();
		$request->setControllerExtensionName('pb_notifications');

		$this->standaloneView->assign('notifications', $this->notifications);
		$this->standaloneView->assignMultiple([
			'notifications' => $this->notifications
		]);

		return $this->standaloneView->render();

	}

	/**
	 * This item has no drop down
	 *
	 * @return bool
	 */
	public function hasDropDown(){
		return true;
	}

	/**
	 * Get the drop down menu
	 *
	 * @return string HTML
	 */
	public function getDropDown(){

		if (!$this->checkAccess()) {
			return '';
		}

		$this->standaloneView->setTemplatePathAndFilename($this->extPath . 'Resources/Private/Templates/ToolbarMenu/DropDown.html');

		$request = $this->standaloneView->getRequest();
		$request->setControllerExtensionName('pb_notifications');

		$maxNumberOfNotificationsInToolbar = ExtensionConfigurationUtility::getMaxNumberOfNotificationsInToolbar();
		if(!intval($maxNumberOfNotificationsInToolbar) > 0){
			$maxNumberOfNotificationsInToolbar = 1000;
		}


		// Don't get the link running on TYPO3 8... :-(
		// => So at the moment we do not show the link to al notifications
		$t3Version = substr(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), 0, 1);

		$this->standaloneView->assignMultiple([
			'notificationListUrl' => BackendUtility::getModuleUrl('user_PbNotificationsNotifications'),
			'onlyUnreadNotifications' => $this->onlyUnreadNotifications,
			'maxNumberOfNotificationsInToolbar' => $maxNumberOfNotificationsInToolbar,
			't3Version' => $t3Version
		]);

		return $this->standaloneView->render();

	}

	/**
	 * No additional attributes
	 *
	 * @return string List item HTML attibutes
	 */
	public function getAdditionalAttributes(){
		return [];
	}

	/**
	 * Position relative to others
	 *
	 * @return int
	 */
	public function getIndex(){
		return 30;
	}


	// =====================================================================================================================================
	// Other functions (not obliged in Interface)
	// =====================================================================================================================================

	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	// Ajax
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

	/**
	 * Renders the menuItem as ajax call
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 */
	public function renderMenuItem(ServerRequestInterface $request, ResponseInterface $response){
		$response->getBody()->write($this->getItem());
		$response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
		return $response;
	}


	/**
	 * Renders the menu as ajax call
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 */
	public function renderMenu(ServerRequestInterface $request, ResponseInterface $response){
		$response->getBody()->write($this->getDropDown());
		$response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
		return $response;
	}


	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	// Hooks
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

	/**
	 * Called as a hook in \TYPO3\CMS\Backend\Utility\BackendUtility::setUpdateSignal (NotificationController)
	 * Updates the notification menu
	 *
	 * @param array $params
	 * @param $ref
	 */
	public function updateMenuHook(&$params, $ref){
		$params['JScode'] = '
			if (top && top.TYPO3.PbNotificationsMenu) {
				top.TYPO3.PbNotificationsMenu.updateMenu();
			}
		';
	}


	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	// Miscellaneous
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

	/**
	 * Returns the current BE user.
	 *
	 * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
	 */
	protected function getBackendUser()	{
		return $GLOBALS['BE_USER'];
	}

	/**
	 * Returns LanguageService
	 *
	 * @return \TYPO3\CMS\Lang\LanguageService
	 */
	protected function getLanguageService() {
		return $GLOBALS['LANG'];
	}


	/**
	 * Returns current PageRenderer
	 *
	 * @return \TYPO3\CMS\Core\Page\PageRenderer
	 */
	protected function getPageRenderer(){
		return GeneralUtility::makeInstance(PageRenderer::class);
	}

}
