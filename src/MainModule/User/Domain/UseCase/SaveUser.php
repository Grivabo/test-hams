<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\UseCase;

use App\MainModule\User\Domain\Entity\User;
use App\MainModule\User\Domain\Infrastructure\UserRepositoryInterface;
use App\Shared\EntityHistory\Domain\Event\FieldsChangEventHandler;
use App\Shared\EntityHistory\Domain\Event\FieldsChangEventHandlerClosure;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Process of save user include validation and history journal
 */
final readonly class SaveUser
{
    /**
     * @param ValidatorInterface $validator
     * @param UserRepositoryInterface $userRepository
     * @param FieldsChangEventHandler $changEventHandler
     */
    public function __construct(
        private ValidatorInterface $validator,
        private UserRepositoryInterface $userRepository,
        private FieldsChangEventHandler $changEventHandler,
    )
    {
    }

    /**
     * @param User $user
     * @return void
     * @throws ValidationFailedException
     */
    public function save(User $user): void
    {
        $constraintViolationList = $this->validator->validate($user);
        if (count($constraintViolationList) > 0) {
            throw new ValidationFailedException($user, $constraintViolationList);
        }

        $this->changEventHandler->prepareHandler(
            itemType: User::HISTORY_EVENT_ITEM_TYPE
        );

        // TODO add exception to PhpDoc
        $this->userRepository->save(
            $user,
            new FieldsChangEventHandlerClosure($this->changEventHandler)
        );
    }
}