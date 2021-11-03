<?php
namespace PeterBenke\PbNotifications\Domain\Model;

/**
 * TYPO3
 */
use TYPO3\CMS\Extbase\Annotation as Extbase; // Needed for validation => do not remove!
use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Php
 */
use DateTime;

/**
 * Class Notification
 * @package PeterBenke\PbNotifications\Domain\Model
 * @author Peter Benke <info@typomotor.de>
 */
class Notification extends AbstractEntity
{

	/**
	 * @var DateTime
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
	 * @var ObjectStorage<FileReference>
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
     * @var ObjectStorage<BackendUser>
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
        $this->markedAsRead = new ObjectStorage();
    }

	/**
	 * @return DateTime|null
	 */
	public function getDate(): ?DateTime
	{
		return $this->date;
	}

	/**
	 * @param DateTime|null $date
	 */
	public function setDate(?DateTime $date){
		$this->date = $date;
	}

    /**
     * @return string $title
     */
    public function getTitle(): string
	{
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string $content
     */
    public function getContent(): string
	{
        // @extensionScannerIgnoreLine
        return $this->content;
    }
    
    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        // @extensionScannerIgnoreLine
        $this->content = $content;
    }

	/**
	 * @return ObjectStorage<FileReference>|null
	 */
	public function getImages(): ?ObjectStorage
	{
		return $this->images;
	}

	/**
	 * @param ObjectStorage<FileReference>|null $images
	 */
	public function setImages(?ObjectStorage $images) {
		$this->images = $images;
	}

	/**
     * @return int $type
     */
    public function getType(): int
	{
        return $this->type;
    }
    
    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

	/**
	 * @return string
	 */
	public function getBeGroups(): string
	{
		return $this->beGroups;
	}

	/**
	 * @param string $beGroups
	 */
	public function setBeGroups(string $beGroups) {
		$this->beGroups = $beGroups;
	}

    /**
     * @param BackendUser $markedAsRead
     */
    public function addMarkedAsRead(BackendUser $markedAsRead)
    {
        $this->markedAsRead->attach($markedAsRead);
    }
    
    /**
     * @param BackendUser $markedAsReadToRemove backend user to be removed
     */
    public function removeMarkedAsRead(BackendUser $markedAsReadToRemove)
    {
        $this->markedAsRead->detach($markedAsReadToRemove);
    }
    
    /**
     * @return ObjectStorage<BackendUser>|null
     */
    public function getMarkedAsRead(): ?ObjectStorage
	{
        return $this->markedAsRead;
    }
    
    /**
     * @param ObjectStorage<BackendUser>|null $markedAsRead
     */
    public function setMarkedAsRead(?ObjectStorage $markedAsRead)
    {
        $this->markedAsRead = $markedAsRead;
    }

}