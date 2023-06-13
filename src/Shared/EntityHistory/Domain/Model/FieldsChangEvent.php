<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Model;

/**
 * Created in infrastructure level after insert and update
 */
final readonly class FieldsChangEvent
{
    /**
     * @param FieldChang[] $changes
     */
    public function __construct(
        public string $itemId,
        public array $changes = [],
    )
    {
    }
}