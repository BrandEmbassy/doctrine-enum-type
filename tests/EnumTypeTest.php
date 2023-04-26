<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\MarcMabeEnumBridge;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;
use function assert;
use function getenv;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @final
 */
class EnumTypeTest extends TestCase
{
    private DatabaseManager $databaseManager;


    public function testShouldSaveAndLoadEnumValue(): void
    {
        $user = new User('Foo Honza', Gender::get(Gender::MALE));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        assert($foundUser instanceof User);
        Assert::same(Gender::get(Gender::MALE), $foundUser->getGender());
    }


    /**
     * @dataProvider carColorProvider
     */
    public function testShouldWorkWithNullableEnumValue(?Color $carColor): void
    {
        $car = new Car('Skoda', $carColor);

        $this->databaseManager->persist($car);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(Car::class);
        $foundCar = $repository->find($car->getId());

        assert($foundCar instanceof Car);
        Assert::same($carColor, $foundCar->getColor());
    }


    /**
     * @return mixed[]
     */
    public function carColorProvider(): array
    {
        return [
            [Color::get(Color::BLACK)],
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
        parent::tearDown();

        $this->databaseManager->dropSchema();
    }
}

if (getenv(Environment::RUNNER) !== false) {
    (new EnumTypeTest())->run();
}
