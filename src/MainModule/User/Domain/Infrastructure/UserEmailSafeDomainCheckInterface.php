<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Infrastructure;

/**
 * Check user email domain using state
 */
interface UserEmailSafeDomainCheckInterface
{
    /**
     * @param string $emailDomain
     * @return bool
     */
    public function isSafeDomain(string $emailDomain): bool;
}