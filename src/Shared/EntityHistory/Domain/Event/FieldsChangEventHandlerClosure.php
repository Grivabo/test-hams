<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\Event;

use App\Shared\EntityHistory\Domain\Model\FieldsChangEvent;

/**
 * For typehint as Closure
 */
final readonly class FieldsChangEventHandlerClosure
{
    /**
     * @param FieldsChangEventHandler $changEventHandler
     */
    public function __construct(
        private FieldsChangEventHandler $changEventHandler,
    )
    {
    }

    /**
     * @param FieldsChangEvent $fieldsChangEvent
     * @return void
     */
    public function __invoke(FieldsChangEvent $fieldsChangEvent)
    {
        $this->changEventHandler->handel($fieldsChangEvent);
    }
}