<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Model;

use App\Shared\EntityHistory\Domain\Entity\HistoryMetaData;

/**
 * Without raw data. Only classes.
 */
final class FieldsChangMeta
{
    public const TYPE = 'FieldsChangMeta';

    /**
     * @var FieldChang[]
     */
    private array $changes;

    /**
     * @param HistoryMetaData $metaData
     * @return false|self
     */
    public static function from(HistoryMetaData $metaData): self|false
    {
        if (self::TYPE !== $metaData->getType()) {
            return false;
        }

        $fieldsChangMeta = new self();
        $fieldsChangMeta->changes = array_map(
            static fn(array $raw) => FieldChang::fromArray($raw),
            $metaData->getMeta()['changes']
        );

        return $fieldsChangMeta;
    }

    /**
     * @param FieldsChangEvent $changEvent
     * @return HistoryMetaData
     */
    public static function getMeta(FieldsChangEvent $changEvent): HistoryMetaData
    {
        $meta['changes'] = array_map(
            static fn(FieldChang $chang) => $chang->toArray(),
            $changEvent->changes
        );

        $historyMetaData = new HistoryMetaData();
        $historyMetaData->setType(self::TYPE);
        $historyMetaData->setMeta($meta);

        return $historyMetaData;
    }

    // ----------------- getters and setters -----------------

    /**
     * @return array
     */
    public function getChanges(): array
    {
        return $this->changes;
    }
}