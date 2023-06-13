<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Event;

use App\Shared\EntityHistory\Domain\Entity\History;
use App\Shared\EntityHistory\Domain\Model\EventTypeEnum;
use App\Shared\EntityHistory\Domain\Model\FieldsChangEvent;
use App\Shared\EntityHistory\Domain\Model\FieldsChangMeta;
use App\Shared\EntityHistory\Domain\UseCase\SaveHistory;
use RuntimeException;

/**
 * Handle in the storage engin where the old state is known
 */
final class FieldsChangEventHandler
{
    private ?History $history = null;

    /**
     * @param SaveHistory $saveHistory
     */
    public function __construct(
        private readonly SaveHistory $saveHistory,
    )
    {
    }

    /**
     * @param string $itemType
     * @return void
     */
    public function prepareHandler(
        string $itemType,
    ): void
    {
        $history = History::create();
        $history->setEvenType(EventTypeEnum::FIELDS_CHANG);
        $history->setItemType($itemType);
        $this->history = $history;
    }

    /**
     * @param FieldsChangEvent $fieldsChangEvent
     * @return void
     */
    public function handel(FieldsChangEvent $fieldsChangEvent): void
    {
        null === $this->history
        && throw new RuntimeException('$this->prepareHandler(...) must be called before.');

        if (0 === count($fieldsChangEvent->changes)) {
            return;
        }

        $historyMetaData = FieldsChangMeta::getMeta($fieldsChangEvent);
        $this->history->setMetaData($historyMetaData);
        $this->history->setItemId($fieldsChangEvent->itemId);
        $this->saveHistory->save($this->history);

        $this->history = null;
    }
}