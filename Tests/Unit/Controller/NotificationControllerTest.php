<?php
namespace PeterBenke\PbNotifications\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Peter Benke <info@typomotor.de>
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

/**
 * Test case for class PeterBenke\PbNotifications\Controller\NotificationController.
 *
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \PeterBenke\PbNotifications\Controller\NotificationController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('PeterBenke\\PbNotifications\\Controller\\NotificationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllNotificationsFromRepositoryAndAssignsThemToView()
	{

		$allNotifications = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$notificationRepository = $this->getMock('PeterBenke\\PbNotifications\\Domain\\Repository\\NotificationRepository', array('findAll'), array(), '', FALSE);
		$notificationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allNotifications));
		$this->inject($this->subject, 'notificationRepository', $notificationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('notifications', $allNotifications);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenNotificationToView()
	{
		$notification = new \PeterBenke\PbNotifications\Domain\Model\Notification();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('notification', $notification);

		$this->subject->showAction($notification);
	}
}
