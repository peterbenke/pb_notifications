<?php
namespace PeterBenke\PbNotifications\Domain\Model;


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

/**
 * Notification
 */
class Notification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

	/**
	 * @var \DateTime
	 */
	protected $date = null;

    /**
     * title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';
    
    /**
     * content
     *
     * @var string
     * @validate NotEmpty
     */
    protected $content = '';

	/**
	 * Images
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $images;

    /**
     * type
     *
     * @var int
     * @validate NotEmpty
     */
    protected $type = 0;
    
    /**
     * markedAsRead
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Beuser\Domain\Model\BackendUser>
     */
    protected $markedAsRead = null;
    
    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->markedAsRead = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

	/**
	 * Gets the date
	 *
	 * @return \DateTime
	 */
	public function getDate(){
		return $this->date;
	}
	/**
	 * Returns the date
	 *
	 * @param \DateTime $date date
	 * @return void
	 */
	public function setDate($date){
		$this->date = $date;
	}

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets the content
     *
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

	/**
	 * Returns the images
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Sets the images
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
	 * @return void
	 */
	public function setImages($images) {
		$this->images = $images;
	}


	/**
     * Returns the type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Sets the type
     *
     * @param int $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Adds a BackendUser
     *
     * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsRead
     * @return void
     */
    public function addMarkedAsRead(\TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsRead)
    {
        $this->markedAsRead->attach($markedAsRead);
    }
    
    /**
     * Removes a BackendUser
     *
     * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsReadToRemove The BackendUser to be removed
     * @return void
     */
    public function removeMarkedAsRead(\TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsReadToRemove)
    {
        $this->markedAsRead->detach($markedAsReadToRemove);
    }
    
    /**
     * Returns the markedAsRead
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Beuser\Domain\Model\BackendUser> $markedAsRead
     */
    public function getMarkedAsRead()
    {
        return $this->markedAsRead;
    }
    
    /**
     * Sets the markedAsRead
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Beuser\Domain\Model\BackendUser> $markedAsRead
     * @return void
     */
    public function setMarkedAsRead(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $markedAsRead)
    {
        $this->markedAsRead = $markedAsRead;
    }

}