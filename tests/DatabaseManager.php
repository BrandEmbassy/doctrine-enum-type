<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use function array_map;

/**
 * @final
 * @template T of object
 */
class DatabaseManager
{
    private EntityManager $entityManager;

    /**
     * @var string[]
     */
    private array $entities;

    /**
     * @var EnumTypesManager<T>
     */
    private EnumTypesManager $enumTypesManager;


    /**
     * @param string[] $entities
     * @param EnumTypesManager<T> $enumTypesManager
     */
    public function __construct(EntityManager $entityManager, array $entities, EnumTypesManager $enumTypesManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = $entities;
        $this->enumTypesManager = $enumTypesManager;
    }


    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }


    public function flush(): void
    {
        $this->entityManager->flush();
    }


    /**
     * @param class-string<T> $entityName
     *
     * @return EntityRepository<T>
     *
     * @throws NotSupported
     */
    public function getRepository(string $entityName): EntityRepository
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
     * @return ClassMetadata<object>[]
     */
    private function getEntitiesMetadata(): array
    {
        return array_map(
            fn(string $entityClass): ClassMetadata => $this->entityManager->getClassMetadata($entityClass),
            $this->entities,
        );
    }
}
