<?php
declare(strict_types = 1);

namespace App\MainModule\User\Domain\Entity;

use App\MainModule\User\Domain\Validator as UserAssert;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @class User
 */
final class User
{
    public const HISTORY_EVENT_ITEM_TYPE = 'User';
    public const NAME_LENGTH_MIN = 8;
    public const NAME_LENGTH_MAX = 64;
    public const EMAIL_LENGTH_MAX = 256;
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: self::NAME_LENGTH_MIN,
        max: self::NAME_LENGTH_MAX,
    )]
    #[Assert\Regex('/^[a-z0-9]+$/')]
    #[UserAssert\NameForbiddenWords]
    #[UserAssert\NameUnique]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email(
        mode: Assert\Email::VALIDATION_MODE_HTML5_ALLOW_NO_TLD
    )]
    #[Assert\Length(
        max: self::EMAIL_LENGTH_MAX,
    )]
    #[UserAssert\EmailSafeDomain]
    #[UserAssert\EmailUnique]
    private string $email;

    #[Assert\NotBlank]
    private DateTimeImmutable $created;

    #[Assert\GreaterThanOrEqual(
        propertyPath: "created"
    )]
    private ?DateTimeImmutable $deleted = null;

    private ?string $notes = null;

    /**
     * @return self
     */
    public static function create(): self
    {
        return (new self())->setCreated(new DateTimeImmutable());
    }

    // ----------------- getters and setters -----------------

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param DateTimeImmutable $created
     * @return User
     */
    public function setCreated(DateTimeImmutable $created): User
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDeleted(): ?DateTimeImmutable
    {
        return $this->deleted;
    }

    /**
     * @param DateTimeImmutable|null $deleted
     * @return User
     */
    public function setDeleted(?DateTimeImmutable $deleted): User
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     * @return User
     */
    public function setNotes(?string $notes): User
    {
        $this->notes = $notes;
        return $this;
    }
}