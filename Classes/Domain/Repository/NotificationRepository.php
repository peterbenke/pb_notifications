<?php
namespace PeterBenke\PbNotifications\Domain\Repository;

/**
 * PeterBenke
 */
use PeterBenke\PbNotifications\Domain\Model\Notification;
use PeterBenke\PbNotifications\Utility\ExtensionConfigurationUtility;

/**
 * TYPO3
 */
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Doctrine
 */
use Doctrine\DBAL\DBALException as DoctrineDBALDBALException;
use Doctrine\DBAL\Driver\Exception as DoctrineDBALDriverException;

/**
 * Class NotificationRepository
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationRepository extends Repository
{
    protected Typo3QuerySettings $querySettings;
    protected Typo3Version $typo3Version;
    public function __construct(Typo3QuerySettings $typo3QuerySettings, Typo3Version $typo3Version)
    {
        $this->querySettings = $typo3QuerySettings;
        $this->typo3Version = $typo3Version;
        parent::__construct();
    }


	/**
	 * Initialize
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function initializeObject()
	{
		$notificationsStoragePid = (int)ExtensionConfigurationUtility::getNotificationsStoragePid();

		// If storage pid is set
		if($notificationsStoragePid > 0){
			$this->querySettings->setStoragePageIds([$notificationsStoragePid]);
		// No storage pid is set => don't respect the storage pid
		}else{
			$this->querySettings->setRespectStoragePage(false);
		}

		// Get all notifications in the users current language or language = -1
        $this->querySettings
			->setRespectSysLanguage(true);

        /** @var Typo3Version $typoVersion */
        $typoVersion = GeneralUtility::makeInstance(Typo3Version::class);
        if ($typoVersion->getMajorVersion() < 12) {
            /**
             * DONE
             * deprecated in v11, can be removed entirely
             *   11.0  Deprecation: #89938 - Language mode in Typo3QuerySettings
             * The following methods have been marked as deprecated and will be removed in TYPO3 v12.
             *
             * - :php:`\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::setLanguageMode()`
             * - :php:`\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::getLanguageMode()`
             *
             * The deprecated methods have been used in combination with the non consistent translation handling of
             * Extbase. As that handling mode disappeared, there is no need to migrate these method calls and just
             * stop calling those instead.
             */
             $this->querySettings
                ->setLanguageMode('content_fallback');
        }

        $uc = $GLOBALS['BE_USER']->uc;
		$langUid = $this->getLanguageUidForIsoCode($uc['lang'] ?? '');

		if($langUid) {
			$this->querySettings->setLanguageUid($langUid);
		}

        $this->setDefaultQuerySettings($this->querySettings);

	}

	/**
	 * Find all notifications (overwrite parent::findAll()
	 * @param array $ordering ordering
	 * @return QueryResultInterface|array
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function findAll(array $ordering = ['date' => QueryInterface::ORDER_DESCENDING, 'type' => QueryInterface::ORDER_DESCENDING])
	{
		$query = $this->createQuery();
		$query->setOrderings($ordering);

        /*
        // test
        $queryParser = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
        $sql = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL();
        $parameters = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters();
        */


		return $query->execute();
		// $queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters());
	}

	/**
	 * Find all notifications, that are assigned to the user groups of the user
	 * @return ObjectStorage|object
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function findOnlyNotificationsAssignedToUsersUserGroup()
	{

		/**
		 * @var Notification $notification
		 * @var ObjectStorage<Notification> $notificationsReturn
		 */
		$notifications = $this->findAll();

		// Backend user groups of the current user
		// $beUserGroups = $GLOBALS['BE_USER']->userGroups;
		$beUserGroups = [];
		foreach($GLOBALS['BE_USER']->userGroups as $key => $value){
			$beUserGroups[] = $key;
		}


		// Create object storage
        if ($this->typo3Version->getMajorVersion() < 12) {
            $notificationsReturn = $this->objectManager->get(ObjectStorage::class);
        } else {
            $notificationsReturn = GeneralUtility::makeInstance(ObjectStorage::class);
        }

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the backend user groups assigned to this notification
			$notificationUserGroups = GeneralUtility::intExplode(',', $notification->getBeGroups());

			// If at least one group matches or the notification has no backend groups assigned or the user is admin
			if (
				count(array_intersect($notificationUserGroups, $beUserGroups)) > 0
				||
				empty($notification->getBeGroups())
				||
				$GLOBALS['BE_USER']->isAdmin()
			){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}

	/**
	 * Find all notifications, that are assigned to the user groups of the user and are unread
	 * @return ObjectStorage|object
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function findOnlyUnreadNotificationsAssignedToUsersUserGroup()
	{

		/**
		 * @var Notification $notification
		 * @var ObjectStorage<Notification> $notificationsReturn
		 */
		$notifications = $this->findAll();

		// Backend user groups of the current user
		// $beUserGroups = $GLOBALS['BE_USER']->userGroups;
		$beUserGroups = [];
		foreach($GLOBALS['BE_USER']->userGroups as $key => $value){
			$beUserGroups[] = $key;
		}

		// Backend user id
		$beUserId = $GLOBALS['BE_USER']->user['uid'];

		// Create object storage
        if ($this->typo3Version->getMajorVersion() < 12) {
            $notificationsReturn = $this->objectManager->get(ObjectStorage::class);
        } else {
            $notificationsReturn = GeneralUtility::makeInstance(ObjectStorage::class);
        }

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the backend user groups assigned to this notification
			$notificationUserGroups = GeneralUtility::intExplode(',', $notification->getBeGroups());

			// Get the marked as read (backend user ids, who has read the notification)
			$markedAsReadBeUsers = $notification->getMarkedAsRead();
			$markedAsReadArray = [];

			foreach($markedAsReadBeUsers as $markedAsReadBeUser){
				$markedAsReadArray[] = $markedAsReadBeUser->getUid();
			}

			$notificationIsRead = true;

			// Notification not read yet
			if(!in_array($beUserId, $markedAsReadArray)){
				$notificationIsRead = false;
			}

			if (
				(
					// At least one group matches
					count(array_intersect($notificationUserGroups, $beUserGroups)) > 0
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
			){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}

	/**
	 * Find all unread notifications
	 * @param array $ordering
	 * @return ObjectStorage|object
	 * @author Peter Benke <info@typomotor.de>
	 */
	public function findOnlyUnreadNotifications(array $ordering = ['type' => QueryInterface::ORDER_DESCENDING, 'date' => QueryInterface::ORDER_DESCENDING])
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
        if ($this->typo3Version->getMajorVersion() < 12) {
            $notificationsReturn = $this->objectManager->get(ObjectStorage::class);
        } else {
            $notificationsReturn = GeneralUtility::makeInstance(ObjectStorage::class);
        }

		// Loop through all notifications
		foreach($notifications as $notification){

			// Get the marked as read (backend user ids, who has read the notification)
			$markedAsReadBeUsers = $notification->getMarkedAsRead();
			$markedAsReadArray = [];

			foreach($markedAsReadBeUsers as $markedAsReadBeUser){
				$markedAsReadArray[] = $markedAsReadBeUser->getUid();
			}

			// Notification not read yet
			if(!in_array($beUserId, $markedAsReadArray)){
				$notificationsReturn->attach($notification);
			}

		}

		return $notificationsReturn;

	}

	/**
	 * Returns the current language uid given by the iso code
	 * @param string $isoCode
	 * @return int
	 * @throws DoctrineDBALDBALException
	 * @author Peter Benke <info@typomotor.de>
	 */
	private function getLanguageUidForIsoCode(string $isoCode):int
	{

		if (!$isoCode){
			$isoCode = 'en';
		}
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_language');

		try{

			$result = $queryBuilder->select('uid')->from('sys_language')
				->where($queryBuilder->expr()->like('language_isocode', $queryBuilder->createNamedParameter($isoCode)))
				->execute()
				->fetchAssociative()
			;
			if($result && $result['uid']) {
				return (int)($result['uid']);
			}

		}catch(DoctrineDBALDriverException $e){
		}

		return 0;
	}

}
