<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Additional data about event
 */
class HistoryMetaData
{
    /**
     * It determinate type of meta for map to proper class
     * @var string
     */
    #[Assert\NotBlank]
    private string $type;
    #[Assert\NotBlank]
    private array $meta;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return HistoryMetaData
     */
    public function setType(string $type): HistoryMetaData
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return HistoryMetaData
     */
    public function setMeta(array $meta): HistoryMetaData
    {
        $this->meta = $meta;
        return $this;
    }
}