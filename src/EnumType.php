<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LogicException;

final class EnumType extends Type
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $className;

    /**
     * @var EnumImplementation
     */
    private $enumImplementation;

    public static function setupFor(
        EnumTypeDefinition $enumTypeDefinition,
        EnumImplementation $enumImplementation
    ): void {
        Type::addType($enumTypeDefinition->getName(), self::class);
        $createdType = Type::getType($enumTypeDefinition->getName());
        \assert($createdType instanceof self);
        $createdType->setEnumTypeDefinition($enumTypeDefinition, $enumImplementation);
    }

    public function setEnumTypeDefinition(
        EnumTypeDefinition $enumTypeDefinition,
        EnumImplementation $enumImplementation
    ): void {
        $this->name = $enumTypeDefinition->getName();
        $this->className = $enumTypeDefinition->getClassName();
        $this->enumImplementation = $enumImplementation;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
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
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $this->validateEnumTypeIsSet();

        if (!($value instanceof $this->className)) {
            throw new InvalidArgumentException(\sprintf('Value %s is not type of %s.', $value, $this->className));
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
        if ($this->name === null || $this->className === null) {
            throw new LogicException('Please call \'setEnumTypeDefinition\' method first.');
        }
    }

}
