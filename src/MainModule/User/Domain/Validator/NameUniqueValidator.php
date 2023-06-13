<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use App\MainModule\User\Domain\Infrastructure\UserNameUniqueCheckInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validate unique user name
 */
final class NameUniqueValidator extends ConstraintValidator
{
    /**
     * @param UserNameUniqueCheckInterface $uniqueUserNameCheck
     */
    public function __construct(
        private readonly UserNameUniqueCheckInterface $uniqueUserNameCheck,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        $constraint instanceof NameUnique
        || throw new UnexpectedTypeException($constraint, NameUnique::class);

        if (count($this->context->getViolations()) > 0) {
            return;
        }

        if (false === $this->uniqueUserNameCheck->isUnique($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(NameUnique::NAME_NOT_UNIQUE)
                ->addViolation();
        }
    }
}