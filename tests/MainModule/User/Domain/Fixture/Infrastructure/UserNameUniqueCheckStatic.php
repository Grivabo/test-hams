<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\MainModule\User\Domain\Infrastructure\UserNameUniqueCheckInterface;

/**
 * For tests
 */
class UserNameUniqueCheckStatic implements UserNameUniqueCheckInterface
{
    public const EXISTED_USER_NAME = 'aaabbbcccddd';

    /**
     * @param string $name user name
     * @return bool
     */
    public function isUnique(string $name): bool
    {
        $fNormalize = static fn(string $word) => strtolower(trim($word));
        return $fNormalize(self::EXISTED_USER_NAME) !== $fNormalize($name);
    }
}