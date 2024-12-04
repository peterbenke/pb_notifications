<?php

namespace PeterBenke\PbNotifications\Domain\Repository;

/**
 * PbNotifications
 */

use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Utility\BackendUserUtility;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Doctrine
 */

use Doctrine\DBAL\Exception as DoctrineDBALException;

/**
 * Class NotificationRepository
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationRepository extends Repository
{

    /**
     * Initialize
     */
    public function initializeObject(): void
    {

        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $notificationsStoragePid = ExtensionConfigurationUtility::getNotificationsStoragePid();

        // If storage pid is set
        if (intval($notificationsStoragePid) > 0) {
            $querySettings->setStoragePageIds([$notificationsStoragePid]);
        } else {
            // No storage pid is set => don't respect the storage pid
            $querySettings->setRespectStoragePage(false);
        }

        // Get all notifications in the users current language or language = -1
        $querySettings->setRespectSysLanguage(true);

        $uc = $GLOBALS['BE_USER']->uc;
        $langUid = $this->getLanguageUidForIsoCode($uc['lang'] ?? '');

        if ($langUid) {
            $languageAspect = GeneralUtility::makeInstance(LanguageAspect::class, $langUid);
            $querySettings->setLanguageAspect($languageAspect);
        }

        $this->setDefaultQuerySettings($querySettings);

    }

    /**
     * Find all notifications (overwrite parent::findAll()
     * @param array $ordering ordering
     * @return array|DomainObjectInterface[]|QueryResultInterface
     */
    public function findAll(array $ordering = ['date' => QueryInterface::ORDER_DESCENDING, 'type' => QueryInterface::ORDER_DESCENDING]): array|QueryResultInterface
    {

        $query = $this->createQuery();
        $query->setOrderings($ordering);
        return $query->execute();
        // $queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters());
    }

    /**
     * Find all notifications, that are assigned to the user groups of the user.
     * @return ObjectStorage
     * @throws InvalidQueryException
     */
    public function findNotificationsAssignedToUserGroups():ObjectStorage
    {

        // At first get all notifications
        $notifications = $this->findAll();
        $notificationsAssignedToUserGroups = GeneralUtility::makeInstance(ObjectStorage::class);

        // Loop through all notifications
        /** @var Notification $notification */
        foreach($notifications as $notification) {

            // Get the backend user groups assigned to this notification
            $notificationUserGroups = GeneralUtility::intExplode(',', $notification->getBeGroups());

            // If at least one group matches or the notification has no backend groups assigned or the user is admin
            if (
                count(array_intersect($notificationUserGroups, BackendUserUtility::getBackendUserGroupsForCurrentBackendUser())) > 0
                ||
                empty($notification->getBeGroups())
                ||
                $GLOBALS['BE_USER']->isAdmin()
            ) {
                $notificationsAssignedToUserGroups->attach($notification);
            }

        }

        return $notificationsAssignedToUserGroups;

    }

    /**
     * Find all notifications, that are assigned to the user groups of the user as QueryResultInterface.
     * @return QueryResultInterface|null
     * @throws InvalidQueryException
     */
    public function findNotificationsAssignedToUserGroupsAsQueryResultInterface(): ?QueryResultInterface
    {

        // At first get all notifications
        $notifications = $this->findNotificationsAssignedToUserGroups();

        if ($notifications->count() == 0) {
            return null;
        }

        $notificationUids = [];

        // Loop through all notifications
        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            $notificationUids[] = $notification->getUid();
        }



        // Now get the notifications by the uids, which match to the user group(s)
        // We need to do this that way, because the return has to be an object of type QueryResultInterface, so that the pagination works.
        $query = $this->createQuery();

        $result = $query
            ->matching(
                $query->in('uid', $notificationUids)
            )
        ;

        return $result->execute();

    }



    # Until here everything is done.




    /**
     * Find all notifications, that are assigned to the user groups of the user and are unread
     * @return ObjectStorage<Notification>
     */
    public function findUnreadNotificationsAssignedToUserGroups(): ObjectStorage
    {

        /**
         * @var Notification $notification
         * @var ObjectStorage<Notification> $notificationsReturn
         */
        $notifications = $this->findAll();

        // Backend user id
        $beUserId = $GLOBALS['BE_USER']->user['uid'];

        // Create object storage
        $notificationsReturn = GeneralUtility::makeInstance(ObjectStorage::class);

        // Loop through all notifications
        foreach ($notifications as $notification) {

            // Get the backend user groups assigned to this notification
            $notificationUserGroups = GeneralUtility::intExplode(',', $notification->getBeGroups());

            // Get the marked as read (backend user ids, who has read the notification)
            $markedAsReadBeUsers = $notification->getMarkedAsRead();
            $markedAsReadArray = [];

            foreach ($markedAsReadBeUsers as $markedAsReadBeUser) {
                $markedAsReadArray[] = $markedAsReadBeUser->getUid();
            }

            $notificationIsRead = true;

            // Notification not read yet
            if (!in_array($beUserId, $markedAsReadArray)) {
                $notificationIsRead = false;
            }

            if (
                (
                    // At least one group matches
                    count(array_intersect($notificationUserGroups, BackendUserUtility::getBackendUserGroupsForCurrentBackendUser())) > 0
                    ||
                    // Notification has no backend groups assigned
                    empty($notification->getBeGroups())
                    ||
                    // The user is admin
                    $GLOBALS['BE_USER']->isAdmin()
                )

                &&

                (
                // Notification is unread
                !$notificationIsRead
                )
            ) {
                $notificationsReturn->attach($notification);
            }

        }

        return $notificationsReturn;

    }

    /**
     * Find all unread notifications.
     * @param array $ordering
     * @return ObjectStorage<Notification>
     */
    public function findUnreadNotifications(array $ordering = ['type' => QueryInterface::ORDER_DESCENDING, 'date' => QueryInterface::ORDER_DESCENDING]): ObjectStorage
    {

        /**
         * @var Notification $notification
         * @var BackendUser $markedAsReadBeUser
         * @var ObjectStorage<Notification> $notificationsReturn
         */

        // All notifications
        $notifications = $this->findAll();

        // Backend user id
        $beUserId = $GLOBALS['BE_USER']->user['uid'];

        // Create object storage
        $notificationsReturn = GeneralUtility::makeInstance(ObjectStorage::class);

        // Loop through all notifications
        foreach ($notifications as $notification) {

            // Get the marked as read (backend user ids, who has read the notification)
            $markedAsReadBeUsers = $notification->getMarkedAsRead();
            $markedAsReadArray = [];

            foreach ($markedAsReadBeUsers as $markedAsReadBeUser) {
                $markedAsReadArray[] = $markedAsReadBeUser->getUid();
            }

            // Notification not read yet
            if (!in_array($beUserId, $markedAsReadArray)) {
                $notificationsReturn->attach($notification);
            }

        }

        return $notificationsReturn;

    }

    /**
     * Returns the current language uid given by the iso code
     * @param string $isoCode
     * @return int
     * @author Peter Benke <info@typomotor.de>
     */
    private function getLanguageUidForIsoCode(string $isoCode): int
    {

        if (!$isoCode) {
            $isoCode = 'en';
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_language');

        try {

            $result = $queryBuilder->select('uid')->from('sys_language')->where($queryBuilder->expr()->like('language_isocode', $queryBuilder->createNamedParameter($isoCode)))->executeQuery()
                ->fetchAssociative();
            if ($result && $result['uid']) {
                return (int)($result['uid']);
            }

        } catch (DoctrineDBALException) {
            return 0;
        }

        return 0;
    }

}