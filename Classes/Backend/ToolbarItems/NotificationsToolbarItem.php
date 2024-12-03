<?php
declare(strict_types=1);

namespace PeterBenke\PbNotifications\Backend\ToolbarItems;

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;
use PeterBenke\PbNotifications\Utility\BackendUserUtility;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * Psr
 */

use Psr\Http\Message\ServerRequestInterface;

/**
 * TYPO3
 */

use TYPO3\CMS\Backend\Domain\Model\Element\ImmediateActionElement;
use TYPO3\CMS\Backend\Toolbar\RequestAwareToolbarItemInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class NotificationsToolbarItem
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationsToolbarItem implements ToolbarItemInterface, RequestAwareToolbarItemInterface
{

    /**
     * Class variables
     * =================================================================================================================
     */

    /**
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    /**
     * @var BackendViewFactory
     */
    private BackendViewFactory $backendViewFactory;

    /**
     * @var ObjectStorage<Notification>
     */
    private ObjectStorage $notifications;

    /**
     * @var ObjectStorage<Notification>
     */
    private ObjectStorage $onlyUnreadNotifications;

    /**
     * Constructor
     * @param BackendViewFactory $backendViewFactory
     */

    /**
     * Functions
     * =================================================================================================================
     */

    /**
     * Constructor
     * @param BackendViewFactory $backendViewFactory
     * @throws InvalidQueryException
     */
    public function __construct(BackendViewFactory $backendViewFactory)
    {

        // BackendViewFactory
        $this->backendViewFactory = $backendViewFactory;

        // Repository
        $notificationRepository = GeneralUtility::makeInstance(NotificationRepository::class);

        // All notifications
        $this->notifications = $notificationRepository->findNotificationsAssignedToUserGroups();

        // Only unread notifications
        $this->onlyUnreadNotifications = $notificationRepository->findUnreadNotifications();

        // Load the javaScript module
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
            JavaScriptModuleInstruction::create('@peterBenke/pbNotifications/Toolbar/NotificationsMenu.js')->invoke('init')
        );
    }

    /**
     * Set the request
     * @param ServerRequestInterface $request
     * @return void
     */
    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Checks whether the user has access to this toolbar item
     * @return bool
     */
    public function checkAccess(): bool
    {
        return BackendUserUtility::userHasAccessToNotifications();
    }

    /**
     * Render toolbar icon via Fluid template
     * @return string HTML
     */
    public function getItem(): string
    {
        // composer name (otherwise TYPO3 would look inside vendor/typo3/cms-backend)
        $view = $this->backendViewFactory->create($this->request, ['peterbenke/pb-notifications']);
        $view->assignMultiple([
            'notifications' => $this->notifications,
        ]);
        return $view->render('Backend/ToolbarItem.html');
    }

    /**
     * This item has a drop-down menu or not
     * @return bool
     */
    public function hasDropDown(): bool
    {
        return true;
    }

    /**
     * Render drop-down menu
     * @return string
     */
    public function getDropDown(): string
    {
        if (!$this->checkAccess()) {
            return '';
        }

        // composer name (otherwise TYPO3 would look inside vendor/typo3/cms-backend)
        $view = $this->backendViewFactory->create($this->request, ['peterbenke/pb-notifications']);

        $maxNumberOfNotificationsInToolbar = ExtensionConfigurationUtility::getMaxNumberOfNotificationsInToolbar();
        if (!intval($maxNumberOfNotificationsInToolbar) > 0) {
            $maxNumberOfNotificationsInToolbar = 100;
        }

        $view->assignMultiple([
            'unreadNotifications' => $this->onlyUnreadNotifications,
            'maxNumberOfNotificationsInToolbar' => $maxNumberOfNotificationsInToolbar,
        ]);
        return $view->render('Backend/ToolbarItemDropDown.html');
    }

    /**
     * Return additional attributes
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return [];
    }

    /**
     * Position relative to toolbar items (order)
     * @return int
     */
    public function getIndex(): int
    {
        return 10;
    }

    /**
     * Hooks
     * =================================================================================================================
     */

    /**
     * @param array $params
     * @return void
     * @see /Resources/Public/JavaScript/Toolbar/NotificationsMenu.js: event listener
     * @noinspection PhpUnused
     */
    public function updateMenuHook(array &$params): void
    {
        $params['html'] = ImmediateActionElement::dispatchCustomEvent(
            'peterbenke:pbnotifications:updateRequested',
            null,
            true
        );
    }

}