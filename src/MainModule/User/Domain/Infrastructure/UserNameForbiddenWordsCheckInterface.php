<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Infrastructure;

/**
 * Check user name bad words using state
 */
interface UserNameForbiddenWordsCheckInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function isContainsForbiddenWord(string $name): bool;
}