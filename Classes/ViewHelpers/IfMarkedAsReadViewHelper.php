<?php

namespace PeterBenke\PbNotifications\ViewHelpers;

class IfMarkedAsReadViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Initialize arguments
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments(){
		$this->registerArgument('markedAsRead', 'object', 'Object storage of backend users, who has read this notification');
		parent::initializeArguments();
	}

	/**
	 * Evaluate
	 * @param array|null $arguments
	 * @return bool
	 */
	protected static function evaluateCondition(array $arguments = null) {

		/**
		 * @var $beUserMarkedAsRead \TYPO3\CMS\Beuser\Domain\Model\BackendUser
		 */

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		$markedAsRead = array();
		if(isset($arguments['markedAsRead'])){
			foreach($arguments['markedAsRead'] as $beUserMarkedAsRead){
				$markedAsRead[] = $beUserMarkedAsRead->getUid();
			}
		}

		// Notification already read
		if(in_array($beUserId, $markedAsRead)){
			return true;
		}

		return false;

	}

}