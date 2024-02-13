<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use MyCLabs\Enum\Enum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MyCLabsEnumBridgeTest extends TestCase
{
    /**
     * @var DatabaseManager<Enum<bool|float|int|string>>
     */
    private DatabaseManager $databaseManager;


    public function testShouldWorkWithMyCLabsEnumImplementation(): void
    {
        $user = new User('Foo Honza', new Gender(Gender::MALE));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertEquals(Gender::MALE, $foundUser->getGender()->getValue());
    }


    protected function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new MyCLabsEnumBridge());
        $databaseManagerBuilder->addEntity(User::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumGender', Gender::class);
        $this->databaseManager = $databaseManagerBuilder->build();

        $this->databaseManager->createSchema();
    }


    protected function tearDown(): void
    {
        $this->databaseManager->dropSchema();

        parent::tearDown();
    }
}
