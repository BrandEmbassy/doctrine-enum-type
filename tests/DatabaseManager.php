<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;

final class DatabaseManager
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string[]
     */
    private $entities;

    /**
     * @var EnumTypesManager
     */
    private $enumTypesManager;

    /**
     * @param EntityManager $entityManager
     * @param string[] $entities
     * @param EnumTypesManager $enumTypesManager
     */
    public function __construct(EntityManager $entityManager, array $entities, EnumTypesManager $enumTypesManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = $entities;
        $this->enumTypesManager = $enumTypesManager;
    }

    /**
     * @param object $entity
     */
    public function persist($entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function getRepository(string $entityName): ObjectRepository
    {
        return $this->entityManager->getRepository($entityName);
    }

    public function createSchema(): void
    {
        $this->enumTypesManager->initializeEnumTypes();

        $tool = new SchemaTool($this->entityManager);
        $tool->createSchema($this->getEntitiesMetadata());
    }

    public function dropSchema(): void
    {
        $tool = new SchemaTool($this->entityManager);
        $tool->dropSchema($this->getEntitiesMetadata());
    }

    /**
     * @return ClassMetadata[]
     */
    private function getEntitiesMetadata(): array
    {
        return \array_map(
            function (string $entityClass): ClassMetadata {
                return $this->entityManager->getClassMetadata($entityClass);
            },
            $this->entities
        );
    }

}
