<?php

namespace PeterBenke\PbNotifications\Tests\Unit\Domain\Model;

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
 * Test case for class \PeterBenke\PbNotifications\Domain\Model\Notification.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Peter Benke <info@typomotor.de>
 */
class NotificationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \PeterBenke\PbNotifications\Domain\Model\Notification
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \PeterBenke\PbNotifications\Domain\Model\Notification();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle()
	{
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getContentReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getContent()
		);
	}

	/**
	 * @test
	 */
	public function setContentForStringSetsContent()
	{
		$this->subject->setContent('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'content',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getVisibleUntilMarkedAsReadReturnsInitialValueForBool()
	{
		$this->assertSame(
			FALSE,
			$this->subject->getVisibleUntilMarkedAsRead()
		);
	}

	/**
	 * @test
	 */
	public function setVisibleUntilMarkedAsReadForBoolSetsVisibleUntilMarkedAsRead()
	{
		$this->subject->setVisibleUntilMarkedAsRead(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'visibleUntilMarkedAsRead',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getVisibleFromReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getVisibleFrom()
		);
	}

	/**
	 * @test
	 */
	public function setVisibleFromForDateTimeSetsVisibleFrom()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setVisibleFrom($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'visibleFrom',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getVisibleUntilReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getVisibleUntil()
		);
	}

	/**
	 * @test
	 */
	public function setVisibleUntilForDateTimeSetsVisibleUntil()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setVisibleUntil($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'visibleUntil',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTypeReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setTypeForIntSetsType()
	{	}

	/**
	 * @test
	 */
	public function getMarkedAsReadReturnsInitialValueForBackendUser()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getMarkedAsRead()
		);
	}

	/**
	 * @test
	 */
	public function setMarkedAsReadForObjectStorageContainingBackendUserSetsMarkedAsRead()
	{
		$markedAsRead = new \TYPO3\CMS\Beuser\Domain\Model\BackendUser();
		$objectStorageHoldingExactlyOneMarkedAsRead = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneMarkedAsRead->attach($markedAsRead);
		$this->subject->setMarkedAsRead($objectStorageHoldingExactlyOneMarkedAsRead);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneMarkedAsRead,
			'markedAsRead',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addMarkedAsReadToObjectStorageHoldingMarkedAsRead()
	{
		$markedAsRead = new \TYPO3\CMS\Beuser\Domain\Model\BackendUser();
		$markedAsReadObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$markedAsReadObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($markedAsRead));
		$this->inject($this->subject, 'markedAsRead', $markedAsReadObjectStorageMock);

		$this->subject->addMarkedAsRead($markedAsRead);
	}

	/**
	 * @test
	 */
	public function removeMarkedAsReadFromObjectStorageHoldingMarkedAsRead()
	{
		$markedAsRead = new \TYPO3\CMS\Beuser\Domain\Model\BackendUser();
		$markedAsReadObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$markedAsReadObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($markedAsRead));
		$this->inject($this->subject, 'markedAsRead', $markedAsReadObjectStorageMock);

		$this->subject->removeMarkedAsRead($markedAsRead);

	}
}
