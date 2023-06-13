<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\Fixture\Infrastructure;

use App\MainModule\User\Domain\Infrastructure\UserNameForbiddenWordsCheckInterface;

/**
 * For tests
 */
final class UserNameForbiddenWordsCheckStatic implements UserNameForbiddenWordsCheckInterface
{
    public const STOP_WORD_1 = 'acd';

    public const STOP_WORDS = [
        self::STOP_WORD_1,
    ];

    /**
     * @param string $name user name
     * @return bool
     */
    public function isContainsForbiddenWord(string $name): bool
    {
        $fNormalize = static fn(string $word) => strtolower($word);
        $nameNormalized = $fNormalize($name);
        foreach (self::STOP_WORDS as $stopWord) {
            if (str_contains($nameNormalized, $fNormalize($stopWord))) return true;
        }
        return false;
    }
}