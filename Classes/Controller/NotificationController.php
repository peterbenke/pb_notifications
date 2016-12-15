<?php
namespace PeterBenke\PbNotifications\Controller;


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

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * NotificationController
 */
class NotificationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController{

	/**
	 * notificationRepository
	 *
	 * @var \PeterBenke\PbNotifications\Domain\Repository\NotificationRepository
	 * @inject
	 */
	protected $notificationRepository = null;

	/**
	 * backendUserRepository
	 * @var \TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $backendUserRepository = null;

	/**
	 * persistManager
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 */
	protected $persistManager = null;


	// =====================================================================================================================================
	// Common functions
	// =====================================================================================================================================

	/**
	 * initialize
	 */
	protected function initializeAction(){
		$this->backendUserRepository = $this->objectManager->get('TYPO3\\CMS\\Beuser\\Domain\\Repository\\BackendUserRepository');
		$this->persistManager = $this->objectManager->get('TYPO3\\CMS\Extbase\\Persistence\\Generic\\PersistenceManager');
	}

	/**
	 * Sets the notification to read or to unread
	 * @param $readUnread
	 */
	private function setReadUnread($readUnread){

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $beUser \TYPO3\CMS\Beuser\Domain\Model\BackendUser
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

	// =====================================================================================================================================
	// Actions
	// =====================================================================================================================================

	/**
	 * Action list
	 * @return void
	 */
	public function listAction(){

		$notifications = $this->notificationRepository->findAll();

		$this->view->assignMultiple(
			array(
				'notifications' => $notifications,
				'user' => $GLOBALS['BE_USER']->user
			)
		);
	}

	/**
	 * Action markAsRead
	 */
	public function markAsReadAction(){
		$this->setReadUnread('read');
		$this->redirect('list');
	}

	/**
	 * Action markAsUnread
	 */
	public function markAsUnreadAction(){
		$this->setReadUnread('unread');
		$this->redirect('list');
	}

	/**
	 * Action show
	 * @param \PeterBenke\PbNotifications\Domain\Model\Notification $notification
	 * @return void
	 */
	public function showAction(\PeterBenke\PbNotifications\Domain\Model\Notification $notification){
		$this->view->assign('notification', $notification);
	}


}