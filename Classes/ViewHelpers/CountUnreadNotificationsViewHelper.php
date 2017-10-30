<?php

namespace PeterBenke\PbNotifications\ViewHelpers;

class CountUnreadNotificationsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render
	 * @param $notifications \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PeterBenke\PbNotifications\Domain\Model\Notification>
	 * @return int
	 */
	protected static function render(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $notifications) {

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $beUserMarkedAsRead \TYPO3\CMS\Beuser\Domain\Model\BackendUser
		 */

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		$unreadNotifications = 0;

		if(isset($notifications)){
			foreach($notifications as $notification){

				$markedAsReadObjects = $notification->getMarkedAsRead();
				$markedAsRead = array();

				foreach($markedAsReadObjects as $beUserMarkedAsRead){
					$markedAsRead[] = $beUserMarkedAsRead->getUid();
				}

				// Unread notification
				if(!in_array($beUserId, $markedAsRead)){
					$unreadNotifications++;
				}

			}
		}

		return $unreadNotifications;

	}

}