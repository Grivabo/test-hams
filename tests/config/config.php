<?php
declare(strict_types = 1);

/** @var ContainerBuilder $container */

/** @var PhpFileLoader $loader */

use App\MainModule\User\Domain\Infrastructure\UserEmailSafeDomainCheckInterface;
use App\MainModule\User\Domain\Infrastructure\UserEmailUniqueCheckInterface;
use App\MainModule\User\Domain\Infrastructure\UserNameForbiddenWordsCheckInterface;
use App\MainModule\User\Domain\Infrastructure\UserNameUniqueCheckInterface;
use App\MainModule\User\Domain\Infrastructure\UserRepositoryInterface;
use App\MainModule\User\Domain\Validator\AnnotationsValidatorFactory;
use App\Shared\EntityHistory\Doamin\Infrastructure\HistoryRepositoryInterface;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\HistoryRepositoryStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserEmailSafeDomainCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserEmailUniqueCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserNameForbiddenWordsCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserNameUniqueCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserRepositoryStatic;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(true);

$loader->registerClasses(
    $definition,
    'App\\',
    '../../src/*',
    '../../src/**/{Entity,Model}'
);

$container->setAlias(Psr\Container\ContainerInterface::class, 'service_container');

$container->autowire(
    ValidatorInterface::class,
    AnnotationsValidatorFactory::class
)
    ->setPublic(true)
    ->setFactory([AnnotationsValidatorFactory::class, 'createValidator']);

$container->autowire(
    ConstraintValidatorFactoryInterface::class,
    ContainerConstraintValidatorFactory::class
);

// ----------------------- Infrastructure for tests ---------------------------

$container->register(
    UserNameForbiddenWordsCheckInterface::class,
    UserNameForbiddenWordsCheckStatic::class
);

$container->register(
    UserEmailUniqueCheckInterface::class,
    UserEmailUniqueCheckStatic::class
);

$container->register(
    UserNameUniqueCheckInterface::class,
    UserNameUniqueCheckStatic::class
);

$container->register(
    UserEmailSafeDomainCheckInterface::class,
    UserEmailSafeDomainCheckStatic::class
);

$container->register(
    UserRepositoryInterface::class,
    UserRepositoryStatic::class
)->setPublic(true);

$container->register(
    HistoryRepositoryInterface::class,
    HistoryRepositoryStatic::class
)->setPublic(true);
