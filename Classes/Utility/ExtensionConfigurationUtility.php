<?php

namespace PeterBenke\PbNotifications\Utility;

/**
 * TYPO3
 */

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ExtensionConfigurationUtility
 * @author Peter Benke <info@typomotor.de>
 */
class ExtensionConfigurationUtility
{

    /**
     * Gets the configuration of the extension
     * @return array|null
     */
    public static function getCurrentConfiguration(): ?array
    {
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        try {
            return $extensionConfiguration->get('pb_notifications');
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
            return null;
        }
    }

    /**
     * Gets the storage pid of the notifications
     * @return mixed
     */
    public static function getNotificationsStoragePid(): mixed
    {
        $configuration = self::getCurrentConfiguration();
        return $configuration['notificationsStoragePid'];
    }

    /**
     * Gets the max number of notifications shown in the toolbar (the number, which is shown, remains the same)
     * @return mixed
     */
    public static function getMaxNumberOfNotificationsInToolbar(): mixed
    {
        $configuration = self::getCurrentConfiguration();
        return $configuration['maxNumberOfNotificationsInToolbar'];
    }

    /**
     * Checks, if a popup should be shown to the user after login
     * @return bool
     * @author Peter Benke <info@typomotor.de>
     */
    public static function forcePopupAfterLogin(): bool
    {
        $configuration = self::getCurrentConfiguration();
        return (bool)$configuration['forceReminderPopUp'];
    }

}