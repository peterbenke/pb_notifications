<?php

namespace PeterBenke\PbNotifications\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class CountUnreadNotificationsViewHelper extends AbstractViewHelper {


    public function initializeArguments()
    {
        $this->registerArgument('notifications', \TYPO3\CMS\Extbase\Persistence\ObjectStorage::class, 'Notifications', false);
    }

	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {

		/**
		 * @var $notification \PeterBenke\PbNotifications\Domain\Model\Notification
		 * @var $beUserMarkedAsRead \TYPO3\CMS\Beuser\Domain\Model\BackendUser
		 */

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		$unreadNotifications = 0;

		if(isset($arguments['notifications'])){
			foreach($arguments['notifications'] as $notification){

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