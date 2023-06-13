<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\MainModule\User\Domain\Entity\User;
use App\MainModule\User\Domain\Infrastructure\UserRepositoryInterface;
use App\Shared\EntityHistory\Domain\Event\FieldsChangEventHandlerClosure;
use App\Shared\EntityHistory\Domain\Model\FieldChang;
use App\Shared\EntityHistory\Domain\Model\FieldsChangEvent;
use Exception;
use RuntimeException;

/**
 * Operations with storage
 */
class UserRepositoryStatic implements UserRepositoryInterface
{
    public const USER_1_ID = 803603;
    private array $items = [];

    public function __construct()
    {
        $this->items[self::USER_1_ID] = User::create()
            ->setId(self::USER_1_ID)
            ->setName('validusername')
            ->setEmail('valid@email.com');
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): ?User
    {
        $user = $this->items[$id] ?? null;
        return clone $user;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @inheritDoc
     */
    public function save(User $user, FieldsChangEventHandlerClosure $changeFieldsHandlerClosure): void
    {
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $user->getId();
        } catch (Exception) {
            $userId = random_int(2 ** 30, 2 ** 32);
            $user->setId($userId);
        }

        $changeFieldsHandlerClosure(
            $this->getFieldsChangEventHandler($user)
        );

        $this->items[$user->getId()] = clone $user;
    }

    /**
     * @param User $newUser
     * @return FieldsChangEvent
     */
    private function getFieldsChangEventHandler(User $newUser): FieldsChangEvent
    {
        $fields = [
            'id',
            'name',
            'email',
            'created',
            'deleted',
            'notes',
        ];

        $oldUser = $this->items[$newUser->getId()] ?? null;

        $changes = [];

        foreach ($fields as $field) {
            $getter = 'get' . $field;
            if (
                ($oldValue = $oldUser?->{$getter}())
                !==
                ($newValue = $newUser->{$getter}())
            ) {
                $changes[] = new FieldChang(
                    fieldName: $field,
                    oldValue: $oldValue,
                    newValue: $newValue,
                );
            }
        }

        return new FieldsChangEvent(
            itemId: (string)$newUser->getId(),
            changes: $changes,
        );
    }
}