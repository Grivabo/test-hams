<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Validator;

use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @class AnnotationsValidatorFactory
 */
final class AnnotationsValidatorFactory
{
    /**
     * @param ConstraintValidatorFactoryInterface $constraintValidatorFactory
     * @return ValidatorInterface
     */
    public static function createValidator(
        ConstraintValidatorFactoryInterface $constraintValidatorFactory
    ): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setConstraintValidatorFactory($constraintValidatorFactory)
            ->getValidator();
    }
}