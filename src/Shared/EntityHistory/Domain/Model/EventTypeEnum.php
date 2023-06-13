<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Model;

use App\Shared\EntityHistory\Domain\Entity\History;

/**
 * For entity history. The type of the history item.
 * @see History
 */
enum EventTypeEnum: string
{
    case FIELDS_CHANG = 'FIELDS_CHANG';
}