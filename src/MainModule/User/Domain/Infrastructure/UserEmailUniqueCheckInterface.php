<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Infrastructure;

/**
 * Check unique user email using state
 */
interface UserEmailUniqueCheckInterface
{
    /**
     * @param string $email
     * @return bool
     */
    public function isUnique(string $email): bool;
}