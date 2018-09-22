<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require_once __DIR__ . '/../../../vendor/autoload.php';

final class MyCLabsEnumBridgeTest extends TestCase
{

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function testShouldWorkWithMyCLabsEnumImplementation(): void
    {
        $user = new User('Foo Honza', new Gender(Gender::MALE));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        \assert($foundUser instanceof User);
        Assert::same(Gender::MALE, $foundUser->getGender()->getValue());
    }

    public function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new MyCLabsEnumBridge());
        $databaseManagerBuilder->addEntity(User::class);
        $databaseManagerBuilder->addEnumTypeDefinition('enumGender', Gender::class);
        $this->databaseManager = $databaseManagerBuilder->build();

        $this->databaseManager->createSchema();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->databaseManager->dropSchema();
    }

}

if (\getenv(Environment::RUNNER) !== false) {
    (new MyCLabsEnumBridgeTest())->run();
}
