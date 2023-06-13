<?php
declare(strict_types = 1);

namespace App\Tests\MainModule\User\Domain\UseCase;

use App\MainModule\User\Domain\Entity\User;
use App\MainModule\User\Domain\Infrastructure\UserRepositoryInterface;
use App\MainModule\User\Domain\UseCase\SaveUser;
use App\Shared\EntityHistory\Doamin\Infrastructure\HistoryRepositoryInterface;
use App\Shared\EntityHistory\Domain\Model\EventTypeEnum;
use App\Shared\EntityHistory\Domain\Model\FieldChang;
use App\Shared\EntityHistory\Domain\Model\FieldsChangMeta;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\HistoryRepositoryStatic;
use App\Tests\MainModule\User\Domain\Fixture\Infrastructure\UserRepositoryStatic;
use App\Tests\support\TestCaseWithContainerBase;
use DateTimeImmutable;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Test save, validations, change history.
 */
class SaveUserTest extends TestCaseWithContainerBase
{
    private UserRepositoryInterface $userRepository;
    private SaveUser $saveUser;
    private HistoryRepositoryStatic $historyRepository;
    private User $user;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->saveUser = $this->container->get(SaveUser::class);
        $this->historyRepository = $this->container->get(HistoryRepositoryInterface::class);

        $this->user = $this->userRepository->find(UserRepositoryStatic::USER_1_ID);
    }

    /**
     * @return void
     */
    public function testSuccessSave(): void
    {
        $newName = 'new0name';

        $this->user->setName($newName);
        $this->saveUser->save($this->user);

        $userNew = $this->userRepository->find(UserRepositoryStatic::USER_1_ID);

        static::assertEquals($newName, $userNew->getName(), 'Wrong User->name');
    }

    /**
     * @return void
     */
    public function testErrorValidationWhenSave(): void
    {
        $this->expectException(ValidationFailedException::class);

        $newName = 'bad name';
        $this->user->setName($newName);
        $this->saveUser->save($this->user);
    }

    /**
     * @return void
     */
    public function testEntitySaveHistory(): void
    {
        self::assertCount(0, $this->historyRepository->getItems());

        $oldUser = clone $this->user;

        $newValues = [
            'name' => 'new0name845422',
            'email' => 'newEmail489884@test.com',
            'deleted' => new DateTimeImmutable(),
            'notes' => 'new note',
        ];

        foreach ($newValues as $fieldName => $newValue) {
            $setter = 'set' . $fieldName;
            $this->user->{$setter}($newValue);
        }

        $this->saveUser->save($this->user);

        $historyItems = $this->historyRepository->getItems();
        self::assertCount(1, $historyItems, 'Wrong historyItems count');

        $newUser = $this->userRepository->find(UserRepositoryStatic::USER_1_ID);

        self::assertEquals(
            $oldUser->getId(),
            $newUser->getId(),
            'Wrong User->id',
        );

        foreach ($newValues as $fieldName => $newValue) {
            $getter = 'get' . $fieldName;
            self::assertEquals(
                $newValue,
                $newUser->{$getter}(),
                "Wrong User->{$fieldName}",
            );
        }

        $historyItem = $historyItems[array_key_first($historyItems)];

        self::assertEquals(
            EventTypeEnum::FIELDS_CHANG,
            $historyItem->getEvenType(),
            'Wrong History->evenType',
        );
        self::assertEquals(
            User::HISTORY_EVENT_ITEM_TYPE,
            $historyItem->getItemType(),
            'Wrong History->itemType',
        );
        self::assertEquals(
            $newUser->getId(),
            $historyItem->getItemId(),
            'Wrong History->itemId',
        );

        {  // ----------- assert HistoryMetaData -----------
            $historyMetaData = $historyItem->getMetaData();
            self::assertEquals(
                FieldsChangMeta::TYPE,
                $historyMetaData->getType(),
                'Wrong HistoryMetaData->type',
            );

            $fieldsChangMeta = FieldsChangMeta::from($historyMetaData);

            $normalizedActualChanges = array_map(
                static fn(FieldChang $chang) => $chang->toArray(),
                $fieldsChangMeta->getChanges(),
            );

            $normalizedExpectedChanges = [];
            foreach ($newValues as $fieldName => $oldValue) {
                $getter = 'get' . $fieldName;
                $normalizedExpectedChanges[] = [
                    'fieldName' => $fieldName,
                    'oldValue' => $oldUser->{$getter}(),
                    'newValue' => $newUser->{$getter}(),
                ];
            }

            self::assertEquals(
                $normalizedExpectedChanges,
                $normalizedActualChanges,
                'Wrong history of changed fields',
            );
        }

        self::assertEquals(
            null,
            $historyItem->getEmitterId(),
            'Wrong History->emitterId',
        );
        self::assertLessThanOrEqual(
            new DateTimeImmutable(),
            $historyItem->getCreated(),
            'Wrong History->created',
        );
    }
}