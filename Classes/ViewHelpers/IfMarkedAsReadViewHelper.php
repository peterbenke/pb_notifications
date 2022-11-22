<?php
namespace PeterBenke\PbNotifications\ViewHelpers;

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
 * Class IfMarkedAsReadViewHelper
 * @author Peter Benke <info@typomotor.de>
 */
class IfMarkedAsReadViewHelper extends AbstractConditionViewHelper
{

	/**
	 * Initialize arguments
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function initializeArguments()
	{
		$this->registerArgument('markedAsRead', 'object', 'Object storage of backend users, who has read this notification');
		parent::initializeArguments();
	}

	/**
	 * Evaluate
	 * @param array|null $arguments
	 * @param RenderingContextInterface $renderingContext
	 * @return bool
	 * @author Peter Benke <info@typomotor.de>
	 */
	public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
	{

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		$markedAsRead = [];
		if(isset($arguments['markedAsRead'])){
			/** @var BackendUser $beUserMarkedAsRead */
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