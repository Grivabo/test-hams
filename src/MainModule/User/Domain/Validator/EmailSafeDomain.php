<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * For validate user email domain
 */
#[Attribute]
final class EmailSafeDomain extends Constraint
{
    public const EMAIL_HAS_UNSAFE_DOMAIN = '4f6136e5-d957-4851-861e-456ea3efd23a';

    protected const ERROR_NAMES = [
        self::EMAIL_HAS_UNSAFE_DOMAIN => 'EMAIL_HAS_UNSAFE_DOMAIN',
    ];

    public string $message = 'The email "{{ string }}" has unsafe domain.';
}