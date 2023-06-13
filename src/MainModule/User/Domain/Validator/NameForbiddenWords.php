<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * For validate forbidden words in user name
 */
#[Attribute]
final class NameForbiddenWords extends Constraint
{
    public const NAME_CONTAIN_FORBIDDEN_WORD = 'add944d7-1ebc-426b-9676-43b575261ef2';

    protected const ERROR_NAMES = [
        self::NAME_CONTAIN_FORBIDDEN_WORD => 'NAME_CONTAIN_FORBIDDEN_WORD',
    ];

    public string $message = 'The name "{{ string }}" contains an forbidden words.';
}