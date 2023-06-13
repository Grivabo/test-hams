<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain;

use App\MainModule\User\Domain\Entity\User;
use App\MainModule\User\Domain\Validator\EmailSafeDomain;
use App\MainModule\User\Domain\Validator\EmailUnique;
use App\MainModule\User\Domain\Validator\NameForbiddenWords;
use App\MainModule\User\Domain\Validator\NameUnique;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserEmailSafeDomainCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserEmailUniqueCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserNameForbiddenWordsCheckStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserNameUniqueCheckStatic;
use App\Tests\support\TestCaseWithContainerBase;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Test user validation rules
 */
final class UserValidationTest extends TestCaseWithContainerBase
{
    public const USER_CREATED = '2023-11-06T14:21:24+03:00';
    protected User $validUser;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::create();

        $user->setName('validusername');
        $user->setEmail('valid@email.com');
        $user->setCreated(new DateTimeImmutable(self::USER_CREATED));

        $this->validUser = $user;
    }

    /**
     * @param UserValidationTestDto $validationDto
     * @return void
     * @throws Exception
     */
    #[DataProvider('provider')]
    public function testValidatin(UserValidationTestDto $validationDto)
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->container->get(ValidatorInterface::class);

        $user = ($validationDto->valueSetter)($this->validUser);

        $constraintViolationList = $validator->validate($user);

        $actualErrorCodes = array_map(
            static fn(ConstraintViolationInterface $error) => $error->getPropertyPath() . ':' . $error->getCode(),
            [...$constraintViolationList],
        );

        $fNormalizeArray = static function(array $array) {
            $array_ = [...$array];
            sort($array_);
            return $array_;
        };

        static::assertEquals(
            $fNormalizeArray($validationDto->expectedErrors),
            $fNormalizeArray($actualErrorCodes),
            'Wrong validation result',
        );
    }

    /**
     * @return array[]
     */
    public static function provider(): array
    {
        return [
            // ---------- User default ----------------------
            'User name empty' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user,
                    expectedErrors: [],
                )],
            // ---------- User empty ----------------------
            'User empty' =>
                [new UserValidationTestDto(
                    valueSetter: fn() => new User(),
                    expectedErrors: [
                        'name:' . NotBlank::IS_BLANK_ERROR,
                        'email:' . NotBlank::IS_BLANK_ERROR,
                        'created:' . NotBlank::IS_BLANK_ERROR,
                    ],
                )],
            // ---------- User name only from a-z and 0-9 ------
            'User name only from a-z and 0-9' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09'
                    ),
                    expectedErrors: [],
                )],
            'User name can\'t has spase' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09 '
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            'User name can\'t has russian' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09ф'
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            'User name can\'t has capital' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09A'
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            'User name can\'t has special !' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09!'
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            'User name can\'t has special .' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09.'
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            'User name can\'t has special 😀' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '123456az09😀'
                    ),
                    expectedErrors: [
                        'name:' . Regex::REGEX_FAILED_ERROR,
                    ],
                )],
            // ---------- User name length ---------------------
            'User name length less then min length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        str_repeat('1', User::NAME_LENGTH_MIN - 1)
                    ),
                    expectedErrors: [
                        'name:' . Length::TOO_SHORT_ERROR,
                    ],
                )],
            'User name length equal min length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        str_repeat('1', User::NAME_LENGTH_MIN)
                    ),
                    expectedErrors: [],
                )],
            'User name length equal max length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        str_repeat('1', User::NAME_LENGTH_MAX)
                    ),
                    expectedErrors: [],
                )],
            'User name length more then max length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        str_repeat('1', User::NAME_LENGTH_MAX + 1)
                    ),
                    expectedErrors: [
                        'name:' . Length::TOO_LONG_ERROR,
                    ],
                )],
            // ---------- User name can't has stop words------------------
            'User name can\'t has stop word' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        '12456' . UserNameForbiddenWordsCheckStatic::STOP_WORD_1
                    ),
                    expectedErrors: [
                        'name:' . NameForbiddenWords::NAME_CONTAIN_FORBIDDEN_WORD,
                    ],
                )],
            // ---------- User name must be unique ------------------
            'User name must be unique' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setName(
                        UserNameUniqueCheckStatic::EXISTED_USER_NAME
                    ),
                    expectedErrors: [
                        'name:' . NameUnique::NAME_NOT_UNIQUE,
                    ],
                )],
            // ---------- User email mast be less then max length ------------------
            'User email mast be less then max length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        str_repeat(
                            'a',
                            User::EMAIL_LENGTH_MAX - strlen(($emailOne = '@valid.com'))
                        ) . $emailOne
                    ),
                    expectedErrors: [
                    ],
                )],
            'User email mast be less then max length' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        str_repeat(
                            'a',
                            User::EMAIL_LENGTH_MAX - strlen(($emailTwo = '@valid2.com')) + 1
                        ) . $emailTwo
                    ),
                    expectedErrors: [
                        'email:' . Length::TOO_LONG_ERROR,
                    ],
                )],
            // ---------- User email must be valid ------------------
            'User email must be valid 1' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        '12@my.com-'
                    ),
                    expectedErrors: [
                        'email:' . Email::INVALID_FORMAT_ERROR,
                    ],
                )],
            'User email must be valid 2' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        '12@@my.com'
                    ),
                    expectedErrors: [
                        'email:' . Email::INVALID_FORMAT_ERROR,
                    ],
                )],
            'User email must be valid 3' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        'a b@my.com'
                    ),
                    expectedErrors: [
                        'email:' . Email::INVALID_FORMAT_ERROR,
                    ],
                )],
            'User email must be valid 4' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        'abmycom'
                    ),
                    expectedErrors: [
                        'email:' . Email::INVALID_FORMAT_ERROR,
                    ],
                )],
            // ---------- User email can't has unsafe domain ------------------
            'User email can\'t has unsafe domain' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        'bad-domain@' . UserEmailSafeDomainCheckStatic::UNSAFE_EMAIL_DOMAIN
                    ),
                    expectedErrors: [
                        'email:' . EmailSafeDomain::EMAIL_HAS_UNSAFE_DOMAIN,
                    ],
                )],
            // ---------- User email must be unique ------------------
            'User email must be unique' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setEmail(
                        UserEmailUniqueCheckStatic::EXISTED_USER_EMAIL
                    ),
                    expectedErrors: [
                        'email:' . EmailUnique::EMAIL_NOT_UNIQUE,
                    ],
                )],
            // ---------- User deleted greater then created  ------------------
            'User deleted equal to created' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setDeleted(
                        new DateTimeImmutable(self::USER_CREATED)
                    ),
                    expectedErrors: [
                    ],
                )],
            'User deleted greater then created' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setDeleted(
                        (new DateTimeImmutable(self::USER_CREATED))
                            ->modify('+1 millisecond')
                    ),
                    expectedErrors: [

                    ],
                )],
            'User deleted less then created' =>
                [new UserValidationTestDto(
                    valueSetter: fn(User $user) => $user->setDeleted(
                        (new DateTimeImmutable(self::USER_CREATED))
                            ->modify('-1 millisecond')
                    ),
                    expectedErrors: [
                        'deleted:' . GreaterThanOrEqual::TOO_LOW_ERROR,
                    ],
                )],
        ];
    }
}