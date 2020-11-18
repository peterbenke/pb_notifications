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
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * TYPO3
 */
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Php
 */
use Closure;

/**
 * Class CountUnreadNotificationsViewHelper
 * @package PeterBenke\PbNotifications\ViewHelpers
 * @author Peter Benke <info@typomotor.de>
 */
class CountUnreadNotificationsViewHelper extends AbstractViewHelper
{

	/**
	 * Initialize
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function initializeArguments()
	{
		$this->registerArgument('notifications', ObjectStorage::class, 'Notifications', false);
	}

	/**
	 * @param array $arguments
	 * @param Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return int|mixed
	 * @author Peter Benke <info@typomotor.de>
	 * @author Sybille Peters <https://github.com/sypets>
	 *
	 */
	public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
	{

		/**
		 * @var Notification $notification
		 * @var BackendUser $beUserMarkedAsRead
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