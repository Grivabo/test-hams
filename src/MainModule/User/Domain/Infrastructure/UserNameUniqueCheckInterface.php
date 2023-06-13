<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Infrastructure;

/**
 * Check unique user name using name
 */
interface UserNameUniqueCheckInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function isUnique(string $name): bool;
}