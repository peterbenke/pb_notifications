<?php
namespace PeterBenke\PbNotifications\Domain\Model;

/**
 * TYPO3
 */
use TYPO3\CMS\Extbase\Annotation as Extbase; // Needed for validation => do not remove!
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Notification
 * @package PeterBenke\PbNotifications\Domain\Model
 * @author Peter Benke <info@typomotor.de>
 */
class Notification extends AbstractEntity
{

	/**
	 * @var \DateTime
	 */
	protected $date = null;

    /**
	 * @Extbase\Validate("NotEmpty")
     * @var string
     */
    protected $title = '';
    
    /**
	 * @Extbase\Validate("NotEmpty")
     * @var string
     */
    protected $content = '';

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $images;

    /**
	 * @Extbase\Validate("NotEmpty")
     * @var int
     */
    protected $type = 0;

	/**
	 * @var string
	 */
	protected $beGroups = '';

    /**
     * markedAsRead
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
     */
    protected function initStorageObjects()
    {
        $this->markedAsRead = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

	/**
	 * @return \DateTime
	 */
	public function getDate(){
		return $this->date;
	}

	/**
	 * @param \DateTime $date date
	 */
	public function setDate($date){
		$this->date = $date;
	}

    /**
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string $content
     */
    public function getContent()
    {
        // @extensionScannerIgnoreLine
        return $this->content;
    }
    
    /**
     * @param string $content
     */
    public function setContent($content)
    {
        // @extensionScannerIgnoreLine
        $this->content = $content;
    }

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

	/**
	 * @return string
	 */
	public function getBeGroups() {
		return $this->beGroups;
	}

	/**
	 * @param string $beGroups
	 */
	public function setBeGroups($beGroups) {
		$this->beGroups = $beGroups;
	}

    /**
     * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsRead
     */
    public function addMarkedAsRead(\TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsRead)
    {
        $this->markedAsRead->attach($markedAsRead);
    }
    
    /**
     * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsReadToRemove The BackendUser to be removed
     */
    public function removeMarkedAsRead(\TYPO3\CMS\Beuser\Domain\Model\BackendUser $markedAsReadToRemove)
    {
        $this->markedAsRead->detach($markedAsReadToRemove);
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Beuser\Domain\Model\BackendUser> $markedAsRead
     */
    public function getMarkedAsRead()
    {
        return $this->markedAsRead;
    }
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Beuser\Domain\Model\BackendUser> $markedAsRead
     */
    public function setMarkedAsRead(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $markedAsRead)
    {
        $this->markedAsRead = $markedAsRead;
    }

}