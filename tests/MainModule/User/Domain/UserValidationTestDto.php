<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain;

use App\MainModule\User\Domain\Entity\User;
use Closure;

/**
 * For strict typing
 */
readonly class UserValidationTestDto
{
    /**
     * @param Closure<<User>, void>|null $valueSetter
     * @param string[] $expectedErrors Format `fieldName:errorCode`.
     * Not DTO because PhpUnit can't show difference well
     * for objects.
     */
    public function __construct(
        public ?Closure $valueSetter,
        public array $expectedErrors = []
    )
    {
    }
}