<?php

namespace PeterBenke\PbNotifications\EventListener\Backend;

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\BackendUserUtility;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */

use TYPO3\CMS\Backend\Controller\Event\AfterBackendPageRenderEvent;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Initializes the modal with message of unread notifications
 * @see https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ApiOverview/Events/Events/Backend/AfterBackendPageRenderEvent.html
 * @author Peter Benke <info@typomotor.de>
 * @noinspection PhpUnused
 */
final class AfterBackendPageRender
{
    public function __invoke(AfterBackendPageRenderEvent $event): void
    {

        // Get the number of unread notifications
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = GeneralUtility::makeInstance(NotificationRepository::class);
        $unreadNotifications = $notificationRepository->findUnreadNotificationsAssignedToUserGroups();

        // Extension setting
        if(!ExtensionConfigurationUtility::forcePopupAfterLogin()){
            return;
        }

        // User not allowed
        if (!BackendUserUtility::userHasAccessToNotifications()) {
            return;
        }

        // No notifications
        if($unreadNotifications->count() === 0){
            return;
        }

        // Contents for the modal
        $contents = [
            'title' => LocalizationUtility::translate('reminder.title', 'PbNotifications'),
            'content' => LocalizationUtility::translate('reminder.content', 'PbNotifications'),
        ];

        // Load the javaScript module for the modal
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
            JavaScriptModuleInstruction::create('@peterBenke/pbNotifications/Reminder/Reminder.js')->invoke('init', $contents)
        );

    }
}