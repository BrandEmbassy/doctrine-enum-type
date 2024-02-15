<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use InvalidArgumentException;
use MyCLabs\Enum\Enum;
use function assert;
use function is_array;
use function is_subclass_of;
use function sprintf;

/**
 * @final
 * @implements EnumImplementation<Enum<bool|float|int|string>>
 */
class MyCLabsEnumBridge implements EnumImplementation
{
    public function isClassSupported(string $className): bool
    {
        return is_subclass_of($className, Enum::class);
    }


    public function assertClassIsSupported(string $className): void
    {
        if (!$this->isClassSupported($className)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Enum type class must be subclass of base enum class '%s'",
                    Enum::class,
                ),
            );
        }
    }


    public function convertDatabaseValueToEnum(string $enumClassName, bool|float|int|string|null $databaseValue): Enum
    {
        assert(is_subclass_of($enumClassName, Enum::class));

        return new $enumClassName($databaseValue);
    }


    public function convertEnumToDatabaseValue(mixed $enum): string
    {
        assert($enum instanceof Enum);

        $value = $enum->getValue();
        assert(!is_array($value), 'Array is not supported as enum value when storing it to database');

        return (string)$value;
    }
}
