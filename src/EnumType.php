<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LogicException;
use MabeEnum\Enum as MabeEnum;
use MyCLabs\Enum\Enum as MyCLabsEnum;
use function assert;
use function sprintf;

/**
 * @final
 */
class EnumType extends Type
{
    private string $name = '';

    /**
     * @var class-string<object>
     */
    private string $className;

    /**
     * @var EnumImplementation<MyCLabsEnum<bool|float|int|string>|MabeEnum|object>
     */
    private EnumImplementation $enumImplementation;


    /**
     * @param EnumImplementation<MyCLabsEnum<bool|float|int|string>|MabeEnum|object> $enumImplementation
     */
    public static function setupFor(
        EnumTypeDefinition $enumTypeDefinition,
        EnumImplementation $enumImplementation
    ): void {
        Type::addType($enumTypeDefinition->getName(), self::class);
        $createdType = Type::getType($enumTypeDefinition->getName());
        assert($createdType instanceof self);
        $createdType->setEnumTypeDefinition($enumTypeDefinition, $enumImplementation);
    }


    /**
     * @param EnumImplementation<MyCLabsEnum<bool|float|int|string>|MabeEnum|object> $enumImplementation
     */
    public function setEnumTypeDefinition(
        EnumTypeDefinition $enumTypeDefinition,
        EnumImplementation $enumImplementation
    ): void {
        $this->name = $enumTypeDefinition->getName();
        $this->className = $enumTypeDefinition->getClassName();
        $this->enumImplementation = $enumImplementation;
    }


    /**
     * @param mixed[] $fieldDeclaration
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }


    /**
     * @param mixed $value
     *
     * @return mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $this->validateEnumTypeIsSet();

        return $this->enumImplementation->convertDatabaseValueToEnum($this->className, $value);
    }


    /**
     * @param mixed $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool|float|int|string|null
    {
        if ($value === null) {
            return null;
        }

        $this->validateEnumTypeIsSet();

        if (!($value instanceof $this->className)) {
            throw new InvalidArgumentException(sprintf('Value %s is not type of %s.', $value, $this->className));
        }

        return $this->enumImplementation->convertEnumToDatabaseValue($value);
    }


    public function getName(): string
    {
        $this->validateEnumTypeIsSet();

        return $this->name;
    }


    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }


    private function validateEnumTypeIsSet(): void
    {
        if ($this->name === '' || $this->className === '') {
            throw new LogicException("Please call 'setEnumTypeDefinition' method first.");
        }
    }
}
