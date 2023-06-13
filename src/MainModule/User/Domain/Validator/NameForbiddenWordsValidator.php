<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use App\MainModule\User\Domain\Infrastructure\UserNameForbiddenWordsCheckInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * For validate forbidden words in user name
 */
final class NameForbiddenWordsValidator extends ConstraintValidator
{
    /**
     * @param UserNameForbiddenWordsCheckInterface $forbiddenWordsInNameProvider
     */
    public function __construct(
        private readonly UserNameForbiddenWordsCheckInterface $forbiddenWordsInNameProvider,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        $constraint instanceof NameForbiddenWords
        || throw new UnexpectedTypeException($constraint, NameForbiddenWords::class);

        if (count($this->context->getViolations()) > 0) {
            return;
        }

        if (true === $this->forbiddenWordsInNameProvider->isContainsForbiddenWord($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode(NameForbiddenWords::NAME_CONTAIN_FORBIDDEN_WORD)
                ->addViolation();
        }
    }
}