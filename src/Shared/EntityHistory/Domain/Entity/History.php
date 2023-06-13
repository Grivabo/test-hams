<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Entity;

use App\Shared\EntityHistory\Domain\Model\EventTypeEnum;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * History of change entities
 */
class History
{
    private int $id;

    #[Assert\NotBlank]
    private EventTypeEnum $evenType;

    #[Assert\NotBlank]
    private string $itemType;

    #[Assert\NotBlank]
    private string $itemId;

    #[Assert\NotBlank]
    private HistoryMetaData $metaData;

    private ?string $emitterId = null;

    #[Assert\NotBlank]
    private DateTimeImmutable $created;

    /**
     * @return self
     */
    public static function create(): self
    {
        return (new self())->setCreated(new DateTimeImmutable());
    }

    // ----------------- getters and setters -----------------

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return History
     */
    public function setId(int $id): History
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return EventTypeEnum
     */
    public function getEvenType(): EventTypeEnum
    {
        return $this->evenType;
    }

    /**
     * @param EventTypeEnum $evenType
     * @return History
     */
    public function setEvenType(EventTypeEnum $evenType): History
    {
        $this->evenType = $evenType;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemType(): string
    {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     * @return History
     */
    public function setItemType(string $itemType): History
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     * @return History
     */
    public function setItemId(string $itemId): History
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return HistoryMetaData
     */
    public function getMetaData(): HistoryMetaData
    {
        return $this->metaData;
    }

    /**
     * @param HistoryMetaData $metaData
     * @return History
     */
    public function setMetaData(HistoryMetaData $metaData): History
    {
        $this->metaData = $metaData;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmitterId(): ?string
    {
        return $this->emitterId;
    }

    /**
     * @param string|null $emitterId
     * @return History
     */
    public function setEmitterId(?string $emitterId): History
    {
        $this->emitterId = $emitterId;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param DateTimeImmutable $created
     * @return History
     */
    public function setCreated(DateTimeImmutable $created): History
    {
        $this->created = $created;
        return $this;
    }
}