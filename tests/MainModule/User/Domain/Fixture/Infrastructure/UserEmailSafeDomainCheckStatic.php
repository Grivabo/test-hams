<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\MainModule\User\Domain\Infrastructure\UserEmailSafeDomainCheckInterface;

/**
 * For tests
 */
class UserEmailSafeDomainCheckStatic implements UserEmailSafeDomainCheckInterface
{
    public const UNSAFE_EMAIL_DOMAIN = 'unsafe-domain.com';

    /**
     * @param string $emailDomain
     * @return bool
     */
    public function isSafeDomain(string $emailDomain): bool
    {
        $fNormalize = static fn(string $word) => strtolower(trim($word));
        return $fNormalize(self::UNSAFE_EMAIL_DOMAIN) !== $fNormalize($emailDomain);
    }
}