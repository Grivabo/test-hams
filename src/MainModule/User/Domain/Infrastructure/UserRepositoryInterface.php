<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Infrastructure;

use App\MainModule\User\Domain\Entity\User;
use App\Shared\EntityHistory\Domain\Event\FieldsChangEventHandlerClosure;

/**
 * Operations with storage.
 */
interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User;

    /**
     * @param array $criteria filter
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     * @return User[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * @param User $user
     * @param FieldsChangEventHandlerClosure $changeFieldsHandlerClosure
     * @return void
     *
     * TODO add exception
     */
    public function save(User $user, FieldsChangEventHandlerClosure $changeFieldsHandlerClosure): void;
}