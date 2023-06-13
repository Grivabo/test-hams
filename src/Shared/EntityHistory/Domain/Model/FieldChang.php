<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Model;

/**
 * Created in infrastructure level after insert and update.
 */
final readonly class FieldChang
{
    /**
     * @param string $fieldName
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function __construct(
        public string $fieldName,
        public mixed $oldValue,
        public mixed $newValue,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'fieldName' => $this->fieldName,
            'oldValue' => $this->oldValue,
            'newValue' => $this->newValue,
        ];
    }

    /**
     * @param array $raw
     * @return self
     */
    public static function fromArray(array $raw): self
    {
        return new self(
            fieldName: $raw['fieldName'],
            oldValue: $raw['oldValue'],
            newValue: $raw['newValue'],
        );
    }
}