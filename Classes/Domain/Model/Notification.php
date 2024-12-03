<?php

namespace PeterBenke\PbNotifications\Domain\Model;

/**
 * TYPO3
 */

use TYPO3\CMS\Extbase\Annotation as Extbase;
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
 * @author Peter Benke <info@typomotor.de>
 */
class Notification extends AbstractEntity
{

    /**
     * @var DateTime|null
     */
    protected ?DateTime $date = null;

    /**
     * @var string
     */
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    /**
     * @var string
     */
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $content = '';

    /**
     * @var ObjectStorage<FileReference>|null
     */
    protected ?ObjectStorage $images;

    /**
     * @var int
     */
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected int $type = 0;

    /**
     * @var string
     */
    protected string $beGroups = '';

    /**
     * markedAsRead
     * @var ObjectStorage<BackendUser>|null
     */
    protected ?ObjectStorage $markedAsRead = null;

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
    protected function initStorageObjects(): void
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
    public function setDate(?DateTime $date): void
    {
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
    public function setTitle(string $title): void
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
    public function setContent(string $content): void
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
    public function setImages(?ObjectStorage $images): void
    {
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
    public function setType(int $type): void
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
    public function setBeGroups(string $beGroups): void
    {
        $this->beGroups = $beGroups;
    }

    /**
     * @param BackendUser $markedAsRead
     */
    public function addMarkedAsRead(BackendUser $markedAsRead): void
    {
        $this->markedAsRead->attach($markedAsRead);
    }

    /**
     * @param BackendUser $markedAsReadToRemove backend user to be removed
     */
    public function removeMarkedAsRead(BackendUser $markedAsReadToRemove): void
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
    public function setMarkedAsRead(?ObjectStorage $markedAsRead): void
    {
        $this->markedAsRead = $markedAsRead;
    }

}