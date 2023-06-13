<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use App\MainModule\User\Domain\Infrastructure\UserEmailSafeDomainCheckInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * For validate user email domain
 */
final class EmailSafeDomainValidator extends ConstraintValidator
{
    /**
     * @param UserEmailSafeDomainCheckInterface $userEmailSafeDomainCheck
     */
    public function __construct(
        private readonly UserEmailSafeDomainCheckInterface $userEmailSafeDomainCheck,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        $constraint instanceof EmailSafeDomain
        || throw new UnexpectedTypeException($constraint, EmailSafeDomain::class);

        if (count($this->context->getViolations()) > 0) {
            return;
        }

        $parts = explode('@', $value);
        $domain = array_pop($parts);

        if (false === $this->userEmailSafeDomainCheck->isSafeDomain($domain)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(EmailSafeDomain::EMAIL_HAS_UNSAFE_DOMAIN)
                ->addViolation();
        }
    }
}