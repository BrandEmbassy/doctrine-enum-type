<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * @final
 * @template T of object
 */
class EnumTypesManager
{
    /**
     * @var EnumTypeDefinition[]
     */
    private array $enumTypeDefinitions = [];

    /**
     * @var EnumImplementation<T>
     */
    private EnumImplementation $enumImplementation;


    /**
     * @param EnumImplementation<T> $enumImplementation
     */
    public function __construct(EnumImplementation $enumImplementation)
    {
        $this->enumImplementation = $enumImplementation;
    }


    /**
     * @param class-string<object> $className
     *
     * @throws InvalidArgumentException when given class is not subclass of base enum class
     */
    public function addEnumTypeDefinition(string $name, string $className): void
    {
        $this->enumImplementation->assertClassIsSupported($className);

        $this->enumTypeDefinitions[] = new EnumTypeDefinition($name, $className);
    }


    /**
     * @return EnumTypeDefinition[]
     */
    public function getEnumTypeDefinitions(): array
    {
        return $this->enumTypeDefinitions;
    }


    public function initializeEnumTypes(): void
    {
        foreach ($this->enumTypeDefinitions as $enumTypeDefinition) {
            if (!Type::hasType($enumTypeDefinition->getName())) {
                EnumType::setupFor($enumTypeDefinition, $this->enumImplementation);
            }
        }
    }
}
