<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\Shared\EntityHistory\Doamin\Infrastructure\HistoryRepositoryInterface;
use App\Shared\EntityHistory\Domain\Entity\History;
use Throwable;

/**
 * Fake storage operations for tests
 */
class HistoryRepositoryStatic implements HistoryRepositoryInterface
{
    /**
     * @var History[]
     */
    private array $items = [];

    /**
     * @inheritDoc
     */
    public function save(History $history): void
    {
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $history->getId();
        } catch (Throwable) {
            $historyId = random_int(2 ** 30, 2 ** 32);  // TODO use constant seed
            $history->setId($historyId);
        }

        $this->items[] = clone $history;
    }

    /**
     * @return History[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}