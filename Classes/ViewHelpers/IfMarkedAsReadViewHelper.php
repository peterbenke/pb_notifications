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
     * @return void
     */
    #[\Override]
    public function initializeArguments(): void
    {
        $this->registerArgument('markedAsRead', 'object', 'Object storage of backend users, who has read this notification');
        parent::initializeArguments();
    }

    /**
     * Evaluate
     * @param array|null $arguments
     * @param RenderingContextInterface $renderingContext
     * @return bool
     */
    #[\Override]
    public static function verdict(?array $arguments, RenderingContextInterface $renderingContext): bool
    {

        // Backend user id
        $beUserId = $GLOBALS['BE_USER']->user['uid'];

        $markedAsRead = [];
        if (isset($arguments['markedAsRead'])) {
            /** @var BackendUser $beUserMarkedAsRead */
            foreach ($arguments['markedAsRead'] as $beUserMarkedAsRead) {
                $markedAsRead[] = $beUserMarkedAsRead->getUid();
            }
        }
        // Notification already read
        return in_array($beUserId, $markedAsRead);

    }

}