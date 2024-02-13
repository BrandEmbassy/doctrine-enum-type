<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\Enum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class EnumBridgeTest extends TestCase
{
    /**
     * @var DatabaseManager<object>
     */
    private DatabaseManager $databaseManager;


    protected function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new EnumBridge());
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
        $user = new User('Foo Honza', Gender::MALE, Index::TWO);

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        Assert::assertInstanceOf(User::class, $foundUser);
        Assert::assertEquals(Gender::MALE, $foundUser->getGender());
        Assert::assertEquals(Index::TWO, $foundUser->getNumericalIndex());
    }
}
