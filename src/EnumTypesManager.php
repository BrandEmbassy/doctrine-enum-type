<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

final class EnumTypesManager
{

    /**
     * @var EnumTypeDefinition[]
     */
    private $enumTypeDefinitions = [];

    /**
     * @var EnumImplementation
     */
    private $enumImplementation;

    public function __construct(EnumImplementation $enumImplementation)
    {
        $this->enumImplementation = $enumImplementation;
    }

    /**
     * @param string $name
     * @param string $className
     * @throws InvalidArgumentException when given class is not subclass of base enum class
     */
    public function addEnumTypeDefinition(string $name, string $className): void
    {
        if (!\is_subclass_of($className, $this->enumImplementation->getBaseEnumClassName())) {
            throw new InvalidArgumentException(
                \sprintf(
                    'Enum type class must be subclass of base enum class \'%s\'',
                    $this->enumImplementation->getBaseEnumClassName()
                )
            );
        }

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
