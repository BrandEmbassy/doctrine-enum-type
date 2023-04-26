<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use function is_subclass_of;
use function sprintf;

/**
 * @final
 */
class EnumTypesManager
{
    /**
     * @var EnumTypeDefinition[]
     */
    private array $enumTypeDefinitions = [];

    private EnumImplementation $enumImplementation;


    public function __construct(EnumImplementation $enumImplementation)
    {
        $this->enumImplementation = $enumImplementation;
    }


    /**
     * @throws InvalidArgumentException when given class is not subclass of base enum class
     */
    public function addEnumTypeDefinition(string $name, string $className): void
    {
        if (!is_subclass_of($className, $this->enumImplementation->getBaseEnumClassName())) {
            throw new InvalidArgumentException(
                sprintf(
                    'Enum type class must be subclass of base enum class \'%s\'',
                    $this->enumImplementation->getBaseEnumClassName(),
                ),
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
