<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\MainModule\User\Domain\Infrastructure\UserEmailUniqueCheckInterface;

/**
 * For tests
 */
class UserEmailUniqueCheckStatic implements UserEmailUniqueCheckInterface
{
    public const EXISTED_USER_EMAIL = 'abc@test.com';

    /**
     * @param string $email user email
     * @return bool
     */
    public function isUnique(string $email): bool
    {
        return self::EXISTED_USER_EMAIL !== $email;
    }
}