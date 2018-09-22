<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\ConsistenceEnum;

use BrandEmbassy\Doctrine\EnumType\DatabaseManager;
use BrandEmbassy\Doctrine\EnumType\DatabaseManagerBuilder;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require_once __DIR__ . '/../../../vendor/autoload.php';

final class ConsistenceEnumBridgeTest extends TestCase
{

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function testShouldWorkWithConsistenceEnumImplementation(): void
    {
        $user = new User('Foo Honza', Gender::get(Gender::MALE));

        $this->databaseManager->persist($user);
        $this->databaseManager->flush();

        $repository = $this->databaseManager->getRepository(User::class);
        $foundUser = $repository->find($user->getId());

        \assert($foundUser instanceof User);
        Assert::same(Gender::get(Gender::MALE), $foundUser->getGender());
    }

    public function setUp(): void
    {
        parent::setUp();

        $databaseManagerBuilder = new DatabaseManagerBuilder(new ConsistenceEnumBridge());
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
    (new ConsistenceEnumBridgeTest())->run();
}
