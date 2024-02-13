<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\Gender;
use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\Index;
use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\MarcMabeEnumBridge;
use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\User;
use MabeEnum\Enum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class EnumTypeTest extends TestCase
{
    /**
     * @var DatabaseManager<Enum>
     */
    private DatabaseManager $databaseManager;


    public function testShouldSaveAndLoadEnumValue(): void
    {
        $user = new User('Foo Honza', Gender::get(Gender::MALE));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertEquals(Gender::get(Gender::MALE), $foundUser->getGender());
    }


    /**
     * @dataProvider carColorProvider
     */
    public function testShouldWorkWithNullableEnumValue(?string $carColor): void
    {
        $carColorEnum = $carColor === null ? null : Color::get($carColor);
        $car = new Car('Skoda', $carColorEnum);

        $this->databaseManager->persist($car);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(Car::class);
        $foundCar = $repository->find($car->getId());

        Assert::assertInstanceOf(Car::class, $foundCar);
        Assert::assertEquals($carColorEnum, $foundCar->getColor());
    }


    /**
     * @return mixed[]
     */
    public static function carColorProvider(): array
    {
        return [
            [Color::BLACK],
            [null],
        ];
    }


    protected function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new MarcMabeEnumBridge());
        $databaseManagerBuilder->addEntity(User::class);
        $databaseManagerBuilder->addEntity(Car::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumGender', Gender::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumColor', Color::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumIndex', Index::class);
        $this->databaseManager = $databaseManagerBuilder->build();

        $this->databaseManager->createSchema();
    }


    protected function tearDown(): void
    {
        $this->databaseManager->dropSchema();

        parent::tearDown();
    }
}
