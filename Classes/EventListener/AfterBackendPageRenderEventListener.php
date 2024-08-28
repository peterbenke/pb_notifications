<?php
declare(strict_types=1);
namespace PeterBenke\PbNotifications\EventListener;

use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;
use TYPO3\CMS\Backend\Controller\Event\AfterBackendPageRenderEvent;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final readonly class AfterBackendPageRenderEventListener
{
    public function __invoke(AfterBackendPageRenderEvent $event): void
    {
        // Create objects
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = GeneralUtility::makeInstance(NotificationRepository::class);

        // Only unread notifications
        $unreadNotifications = $notificationRepository->findOnlyUnreadNotificationsAssignedToUsersUserGroup();

        // We do not need to show a popup to the user after login
        if($unreadNotifications->count() === 0 || !ExtensionConfigurationUtility::forcePopupAfterLogin()){
            return;
        }

        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addInlineLanguageLabelFile('EXT:pb_notifications/Resources/Private/Language/locallang.xlf');
        $pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/PbNotifications/Reminder/Reminder',
            'function(reminder){
                reminder.initModal(true);
            }'
        );
    }
}
