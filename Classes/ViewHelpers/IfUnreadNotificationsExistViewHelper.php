<?php

namespace PeterBenke\PbNotifications\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class IfUnreadNotificationsExistViewHelper extends AbstractConditionViewHelper {

	/**
	 * Initialize arguments
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments(){
		$this->registerArgument('notifications', 'object', 'Object storage of notifications');
		parent::initializeArguments();
	}

	/**
	 * Evaluate
	 * @param array|null $arguments
	 * @return bool
	 */
	public static function verdict(array $arguments, RenderingContextInterface $renderingContext) {

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $beUserMarkedAsRead \TYPO3\CMS\Beuser\Domain\Model\BackendUser
		 */

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		$unreadNotificationsExist = false;

		if(isset($arguments['notifications'])){
			foreach($arguments['notifications'] as $notification){

				$markedAsReadObjects = $notification->getMarkedAsRead();
				$markedAsRead = array();

				foreach($markedAsReadObjects as $beUserMarkedAsRead){
					$markedAsRead[] = $beUserMarkedAsRead->getUid();
				}

				// Unread notification exist
				if(!in_array($beUserId, $markedAsRead)){
					$unreadNotificationsExist = true;
					break;
				}

			}
		}


		return $unreadNotificationsExist;

	}

}