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

		$this->setDefaultQuerySettings($querySettings);

	}

	/**
	 * Find all notifications (overwrite parent::findAll()
	 * @param array $ordering ordering
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findAll(array $ordering = ['type' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING, 'date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING]){

		$query = $this->createQuery();
		$query->setOrderings($ordering);

		return $query->execute();

	}

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