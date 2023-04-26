<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use BrandEmbassy\Doctrine\EnumType\Gender;
use BrandEmbassy\Doctrine\EnumType\Index;
use BrandEmbassy\Doctrine\EnumType\User;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;
use function assert;
use function getenv;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * @final
 */
class MarcMabeEnumBridgeTest extends TestCase
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;


    public function testShouldWorkWithMarcMabeEnumImplementation(): void
    {
        $user = new User('Foo Honza', Gender::get(Gender::MALE), Index::get(Index::TWO));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        assert($foundUser instanceof User);
        Assert::same(Gender::get(Gender::MALE), $foundUser->getGender());
        Assert::same(Index::get(Index::TWO), $foundUser->getNumericalIndex());
    }


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
        parent::tearDown();

        $this->databaseManager->dropSchema();
    }
}

if (getenv(Environment::RUNNER) !== false) {
    (new MarcMabeEnumBridgeTest())->run();
}
