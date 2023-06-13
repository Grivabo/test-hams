<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Doamin\Infrastructure;

use App\Shared\EntityHistory\Domain\Entity\History;

/**
 * Operations with storage
 */
interface HistoryRepositoryInterface
{
    /**
     * @param History $history
     * @return void
     *
     * TODO add exception
     */
    public function save(History $history): void;
}