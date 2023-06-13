<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use App\MainModule\User\Domain\Infrastructure\UserEmailUniqueCheckInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * For validate unique user email
 */
final class EmailUniqueValidator extends ConstraintValidator
{
    /**
     * @param UserEmailUniqueCheckInterface $userEmailUniqueCheck
     */
    public function __construct(
        private readonly UserEmailUniqueCheckInterface $userEmailUniqueCheck,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        $constraint instanceof EmailUnique
        || throw new UnexpectedTypeException($constraint, EmailUnique::class);

        if (count($this->context->getViolations()) > 0) {
            return;
        }

        if (false === $this->userEmailUniqueCheck->isUnique($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(EmailUnique::EMAIL_NOT_UNIQUE)
                ->addViolation();
        }
    }
}