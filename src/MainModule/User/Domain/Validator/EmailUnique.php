<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * For validate unique user email
 */
#[Attribute]
final class EmailUnique extends Constraint
{
    public const EMAIL_NOT_UNIQUE = '85578775-f365-4f47-8286-6bfb14bcdaac';

    protected const ERROR_NAMES = [
        self::EMAIL_NOT_UNIQUE => 'EMAIL_NOT_UNIQUE',
    ];

    public string $message = 'The email "{{ string }}" already exists.';
}