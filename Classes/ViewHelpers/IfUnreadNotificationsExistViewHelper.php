<?php
namespace PeterBenke\PbNotifications\ViewHelpers;

/**
 * PbNotifications
 */
use PeterBenke\PbNotifications\Domain\Model\Notification;

/**
 * TYPO3Fluid
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * TYPO3
 */
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;

/**
 * Class IfUnreadNotificationsExistViewHelper
 * @package PeterBenke\PbNotifications\ViewHelpers
 * @author Peter Benke <info@typomotor.de>
 */
class IfUnreadNotificationsExistViewHelper extends AbstractConditionViewHelper
{

	/**
	 * Initialize arguments
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function initializeArguments()
	{
		$this->registerArgument('notifications', 'object', 'Object storage of notifications');
		parent::initializeArguments();
	}

	/**
	 * Evaluate
	 * @param array|null $arguments
	 * @param RenderingContextInterface $renderingContext
	 * @return bool
	 * @author Peter Benke <info@typomotor.de>
	 * @author Sybille Peters <https://github.com/sypets>
	 */
	public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
	{

		/**
		 * @var Notification $notification
		 * @var BackendUser $beUserMarkedAsRead
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