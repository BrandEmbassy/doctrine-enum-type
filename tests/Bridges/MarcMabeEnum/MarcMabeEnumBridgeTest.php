<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use MabeEnum\Enum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MarcMabeEnumBridgeTest extends TestCase
{
    /**
     * @var DatabaseManager<Enum>
     */
    private DatabaseManager $databaseManager;


    protected function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new MarcMabeEnumBridge());
        $databaseManagerBuilder->addEntity(User::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumGender', Gender::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumIndex', Index::class);
        $this->databaseManager = $databaseManagerBuilder->build();

        $this->databaseManager->createSchema();
    }


    protected function tearDown(): void
    {
        $this->databaseManager->dropSchema();

        parent::tearDown();
    }


    public function testShouldWorkWithMarcMabeEnumImplementation(): void
    {
        $user = new User('Foo Honza', Gender::get(Gender::MALE), Index::get(Index::TWO));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertEquals(Gender::get(Gender::MALE), $foundUser->getGender());
        Assert::assertEquals(Index::get(Index::TWO), $foundUser->getNumericalIndex());
    }
}
