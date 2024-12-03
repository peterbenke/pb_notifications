<?php

namespace PeterBenke\PbNotifications\Utility;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BackendUserUtility
 * @author Peter Benke <info@typomotor.de>
 */
class BackendUserUtility
{

    /**
     * Return a list of all backend user groups of the current backend user.
     * @return array
     */
    public static function getBackendUserGroupsForCurrentBackendUser(): array
    {
        $beUserGroups = [];
        foreach ($GLOBALS['BE_USER']->userGroups as $key => $value) {
            $beUserGroups[] = $key;
        }
        return $beUserGroups;
    }

    /**
     * Check if the current backend user has access to the notifications module
     * @return bool
     */
    public static function userHasAccessToNotifications(): bool
    {
        $beUser = self::getBackendUser();
        if (
            $beUser->isAdmin()
            ||
            GeneralUtility::inList($beUser->groupData['modules'], 'user_pb_notificationsNotifications')
        ) {
            return true;
        }
        return false;
    }

    /**
     * Returns the current backend user
     * @return BackendUserAuthentication|null
     */
    public static function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

}