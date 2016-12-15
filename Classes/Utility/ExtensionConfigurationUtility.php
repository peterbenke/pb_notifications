<?php
namespace PeterBenke\PbNotifications\Utility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Peter Benke <info@typomotor.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class ExtensionConfigurationUtility{

	/**
	 * Gets the configuration of the extension
	 * @return array
	 */
	public static function getCurrentConfiguration(){

		/**
		 * @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
		 * @var \TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility $configurationUtility
		 */
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$configurationUtility = $objectManager->get('TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility');

		$extensionConfiguration = $configurationUtility->getCurrentConfiguration('pb_notifications');
		return $extensionConfiguration;

	}

	/**
	 * Gets the notifications storage pid
	 * @return mixed
	 */
	public static function getNotificationsStoragePid(){

		$configuration = self::getCurrentConfiguration();
		return $configuration['notificationsStoragePid']['value'];

	}

	/**
	 * Gets the max number of notifications shown in the toolbar (the number, which is shown, remains the same)
	 * @return mixed
	 */
	public static function getMaxNumberOfNotificationsInToolbar(){

		$configuration = self::getCurrentConfiguration();
		return $configuration['maxNumberOfNotificationsInToolbar']['value'];

	}

}