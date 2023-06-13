<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Validate unique user name
 */
#[Attribute]
final class NameUnique extends Constraint
{
    public const NAME_NOT_UNIQUE = 'e6d03621-7469-4aa6-b323-f7974148fa6c';

    protected const ERROR_NAMES = [
        self::NAME_NOT_UNIQUE => 'NAME_NOT_UNIQUE',
    ];

    public string $message = 'The name "{{ string }}" already exists.';
}