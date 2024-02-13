<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\Enum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use InvalidArgumentException;
use function enum_exists;

/**
 * @final
 * @implements EnumImplementation<object>
 */
class EnumBridge implements EnumImplementation
{
    public function isClassSupported(string $className): bool
    {
        return enum_exists($className);
    }


    public function assertClassIsSupported(string $className): void
    {
        if (!$this->isClassSupported($className)) {
            throw new InvalidArgumentException('Enum must be a backed Enum');
        }
    }


    public function convertDatabaseValueToEnum(string $enumClassName, bool|float|int|string|null $databaseValue): mixed
    {
        return $databaseValue !== '' ? $enumClassName::from($databaseValue) : null;
    }


    public function convertEnumToDatabaseValue(mixed $enum): bool|float|int|string|null
    {
        return $enum?->value;
    }
}
