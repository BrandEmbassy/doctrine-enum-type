<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Nette\Neon\Neon;
use RuntimeException;

final class DatabaseManagerBuilder
{

    private const CONFIG_FILE = 'config.neon';

    /**
     * @var string[]
     */
    private $entities;

    /**
     * @var EnumTypesManager
     */
    private $enumTypesManager;

    public function __construct(EnumImplementation $enumImplementation)
    {
        $this->enumTypesManager = new EnumTypesManager($enumImplementation);
    }

    public function addEntity(string $entity): void
    {
        $this->entities[] = $entity;
    }

    public function addEnumTypeDefinition(string $name, string $className): void
    {
        $this->enumTypesManager->addEnumTypeDefinition($name, $className);
    }

    public function build(): DatabaseManager
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__], true, null, null, false);
        $entityManager = EntityManager::create($this->getDatabaseConfiguration(), $config);

        return new DatabaseManager($entityManager, $this->entities, $this->enumTypesManager);
    }

    /**
     * @return mixed[]
     */
    private function getDatabaseConfiguration(): array
    {
        $configFilePath = __DIR__ . '/' . self::CONFIG_FILE;
        if (!\file_exists($configFilePath)) {
            throw new RuntimeException('Missing tests/config.neon config file');
        }

        $rawConfig = \file_get_contents($configFilePath);
        if ($rawConfig === false) {
            throw new RuntimeException('Config file tests/config.neon is not readable');
        }

        $config = Neon::decode($rawConfig);

        return $config['parameters']['database'];
    }

}
