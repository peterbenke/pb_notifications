<?php
namespace PeterBenke\PbNotifications\Domain\Repository;


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

use \PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * The repository for Notifications
 */
class NotificationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * initialize
	 */
	public function initializeObject() {

		/** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');

		$notificationsStoragePid = ExtensionConfigurationUtility::getNotificationsStoragePid();

		// If storage pid is set
		if(intval($notificationsStoragePid) > 0){

			$querySettings->setStoragePageIds(array($notificationsStoragePid));

		// No storage pid is set => don't respect the storage pid
		}else{

			$querySettings->setRespectStoragePage(false);

		}
		// jv. some magic to get all nofication in the users actual language or language = -1
        $querySettings->setRespectSysLanguage(true);
        $querySettings->setLanguageMode('content_fallback');
        $uc = unserialize( $GLOBALS['BE_USER']->user['uc'] ) ;
        $lang = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordRaw('sys_language',  'language_isocode="' . $uc['lang'] . '"' , 'uid');
        if( is_array( $lang )) {
            $querySettings->setLanguageUid($lang['uid']);
        }

        $this->setDefaultQuerySettings($querySettings);

	}

	/**
	 * Find all notifications (overwrite parent::findAll()
	 * @param array $ordering ordering
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findAll(array $ordering = ['date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING, 'type' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING]){

		$query = $this->createQuery();
		$query->setOrderings($ordering);


        $result = $query->execute();

        return $result ;

	}

	/**
	 * Find all notifications, that are assigned to the user groups of the user
	 * @return object|\TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function findOnlyNotificationsAssignedToUsersUserGroup(){

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $notificationsReturn \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
		 */

		// All notifications
		$notifications = $this->findAll();

		// Backend user groups of the current user
		// $beUserGroups = $GLOBALS['BE_USER']->userGroups;
		$beUserGroups = array();
		foreach($GLOBALS['BE_USER']->userGroups as $key => $value){
			$beUserGroups[] = $key;
		}


		// Create object storage
		$notificationsReturn = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the backend user groups assigned to this notification
			$notificationUserGroups = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $notification->getBeGroups());

			// If at least one group matches or the notification has no backend groups assigned or the user is admin
			if (
				count(array_intersect($notificationUserGroups, $beUserGroups)) > 0
				||
				empty($notification->getBeGroups())
				||
				$GLOBALS['BE_USER']->isAdmin()
			){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}

	/**
	 * Find all notifications, that are assigned to the user groups of the user and are unread
	 * @return object|\TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function findOnlyUnreadNotificationsAssignedToUsersUserGroup(){

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $notificationsReturn \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
		 */

		// All notifications
		$notifications = $this->findAll();

		// Backend user groups of the current user
		// $beUserGroups = $GLOBALS['BE_USER']->userGroups;
		$beUserGroups = array();
		foreach($GLOBALS['BE_USER']->userGroups as $key => $value){
			$beUserGroups[] = $key;
		}

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		// Create object storage
		$notificationsReturn = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the backend user groups assigned to this notification
			$notificationUserGroups = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $notification->getBeGroups());

			// Get the marked as read (backend user ids, who has read the notification)
			$markedAsReadBeUsers = $notification->getMarkedAsRead();
			$markedAsReadArray = array();

			foreach($markedAsReadBeUsers as $markedAsReadBeUser){
				$markedAsReadArray[] = $markedAsReadBeUser->getUid();
			}

			$notificationIsRead = true;

			// Notification not read yet
			if(!in_array($beUserId, $markedAsReadArray)){
				$notificationIsRead = false;
			}

			if (
				(
					// At least one group matches
					count(array_intersect($notificationUserGroups, $beUserGroups)) > 0
					||
					// Notification has no backend groups assigned
					empty($notification->getBeGroups())
					||
					// The user is admin
					$GLOBALS['BE_USER']->isAdmin()
				)

				&&

				(
					// Notification is unread
					!$notificationIsRead
				)
			){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}

	/**
	 * Find all unread notifications
	 * @param array $ordering
	 * @return object|\TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function findOnlyUnreadNotifications(array $ordering = ['type' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING, 'date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING]){

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $markedAsReadObject \TYPO3\CMS\Beuser\Domain\Model\BackendUser
		 * @var $notificationsReturn \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
		 */

		// All notifications
		$notifications = $this->findAll();

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		// Create object storage
		$notificationsReturn = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the marked as read (backend user ids, who has read the notification)
			$markedAsReadBeUsers = $notification->getMarkedAsRead();
			$markedAsReadArray = array();

			foreach($markedAsReadBeUsers as $markedAsReadBeUser){
				$markedAsReadArray[] = $markedAsReadBeUser->getUid();
			}

			// Notification not read yet
			if(!in_array($beUserId, $markedAsReadArray)){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}


}