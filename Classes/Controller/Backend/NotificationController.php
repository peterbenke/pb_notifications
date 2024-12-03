<?php

namespace PeterBenke\PbNotifications\Controller\Backend;

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Backend\ToolbarItems\NotificationsToolbarItem;
use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Domain\Repository\NotificationRepository;

/**
 * TYPO3
 */

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Psr
 */

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/**
 * Class NotificationController
 * @author Peter Benke <info@typomotor.de>
 */
#[AsController]
class NotificationController extends ActionController
{

    /**
     * Maximum number of items per page
     * @var int
     */
    protected int $itemsPerPage = 10;

    /**
     * Maximum number of links
     * @var int
     */
    protected int $maximumNumberOfLinks = 15;

    /**
     * @var string
     */
    protected string $template = 'Backend/Notification/List.html';

    /**
     * Constructor.
     * Visibility of parameters directly set here, set all parameters, which might be needed.
     * @param ModuleTemplateFactory $moduleTemplateFactory
     * @param PersistenceManager $persistenceManager
     * @param BackendUserRepository $backendUserRepository
     * @param PageRenderer $pageRenderer
     * @param NotificationRepository $notificationRepository
     * @param NotificationsToolbarItem $notificationsToolbarItem
     */
    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly PersistenceManager $persistenceManager,
        protected readonly BackendUserRepository $backendUserRepository,
        protected readonly PageRenderer $pageRenderer,
        protected readonly NotificationRepository $notificationRepository,
        protected readonly NotificationsToolbarItem $notificationsToolbarItem,
    )
    {
    }

    /**
     * Initialize.
     * @return void
     */
    public function initializeAction(): void
    {
        // This works only, if moduleTemplateFactory is used for view => see action function(s)
        $this->pageRenderer->addCssFile('EXT:pb_notifications/Resources/Public/Css/notifications.css', 'stylesheet', 'all', '', false);
        $this->pageRenderer->addJsFooterFile('EXT:pb_notifications/Resources/Public/JavaScript/Gallery/fslightbox.js', 'text/javascript', false, false, '', true);
    }

    /**
     * List notifications.
     * @return ResponseInterface
     * @throws InvalidQueryException
     * @noinspection PhpUnused
     */
    public function listAction(): ResponseInterface
    {

        // Important: write this in that way to enable including css in initializeAction()
        $view = $this->moduleTemplateFactory->create($this->request);

        // Get all notifications assigned to the user groups of the current backend user
        $notifications = $this->notificationRepository->findNotificationsAssignedToUserGroupsAsQueryResultInterface();

        // Pagination
        // see: https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Pagination/Index.html
        $currentPage = $this->request->hasArgument('currentPageNumber')
            ? (int)$this->request->getArgument('currentPageNumber')
            : 1;
        $paginator = new QueryResultPaginator(
            $notifications,
            $currentPage,
            $this->itemsPerPage,
        );
        $pagination = new SlidingWindowPagination(
            $paginator,
            $this->maximumNumberOfLinks,
        );

        $view->assignMultiple(
            [
                'user' => $GLOBALS['BE_USER']->user,
                'notifications' => $notifications,
                'pagination' => $pagination,
                'paginator' => $paginator,
                'currentPage' => $currentPage,
            ]
        );

        return $view->renderResponse($this->template);

    }

    /**
     * Mark notification as read.
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @noinspection PhpUnused
     */
    public function markAsReadAction(): ResponseInterface
    {
        $this->setReadUnread('read');
        return new ForwardResponse('list');
    }

    /**
     * Mark notification as unread.
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @noinspection PhpUnused
     */
    public function markAsUnreadAction(): ResponseInterface
    {
        $this->setReadUnread('unread');
        return new ForwardResponse('list');
    }


    /**
     * Sets the notification to read or to unread
     * @param string $readUnread
     * @return void
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    private function setReadUnread(string $readUnread): void
    {

        /**
         * @var Notification $notification
         * @var BackendUser $beUser
         */

        $beUserId = $GLOBALS['BE_USER']->user['uid'];
        $arguments = $this->request->getArguments();

        $notification = $this->notificationRepository->findByUid(intval($arguments['uid']));
        $beUser = $this->backendUserRepository->findByUid(intval($beUserId));

        if($readUnread == 'read'){
            $notification->addMarkedAsRead($beUser);
        }else{
            $notification->removeMarkedAsRead($beUser);
        }

        $this->notificationRepository->update($notification);
        $this->persistenceManager->persistAll();

        BackendUtility::setUpdateSignal('PbNotificationsToolbar::updateMenu');

    }

    /**
     * Ajax calls, done by /Resources/Public/JavaScript/Toolbar/NotificationsMenu.js
     * =================================================================================================================
     */

    /**
     * Renders the menu item by ajax call
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @noinspection PhpUnused
     */
    public function renderMenuItem(ServerRequestInterface $request): ResponseInterface
    {
        $this->notificationsToolbarItem->setRequest($request);
        return new HtmlResponse($this->notificationsToolbarItem->getItem());
    }

    /**
     * Renders the menu by ajax call
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @noinspection PhpUnused
     */
    public function renderMenu(ServerRequestInterface $request): ResponseInterface
    {
        $this->notificationsToolbarItem->setRequest($request);
        return new HtmlResponse($this->notificationsToolbarItem->getDropDown());
    }

}