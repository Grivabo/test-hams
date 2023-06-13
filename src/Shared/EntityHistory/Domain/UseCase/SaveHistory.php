<?php
declare(strict_types = 1);

namespace App\Shared\EntityHistory\Domain\UseCase;

use App\Shared\EntityHistory\Doamin\Infrastructure\HistoryRepositoryInterface;
use App\Shared\EntityHistory\Domain\Entity\History;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validation and save
 */
final readonly class SaveHistory
{
    /**
     * @param ValidatorInterface $validator
     * @param HistoryRepositoryInterface $historyRepository
     */
    public function __construct(
        private ValidatorInterface $validator,
        private HistoryRepositoryInterface $historyRepository,
    )
    {
    }

    /**
     * @param History $history
     * @return void
     */
    public function save(History $history): void
    {
        $constraintViolationList = $this->validator->validate($history);
        if (count($constraintViolationList) > 0) {
            throw new ValidationFailedException($history, $constraintViolationList);
        }

        // TODO add exception to PhpDoc
        $this->historyRepository->save($history);
    }
}