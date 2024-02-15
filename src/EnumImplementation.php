<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

/**
 * @template TObject of object
 */
interface EnumImplementation
{
    public function isClassSupported(string $className): bool;


    public function assertClassIsSupported(string $className): void;


    /**
     * @template T of TObject
     * @phpstan-param class-string<T> $enumClassName
     * @phpstan-return T
     */
    public function convertDatabaseValueToEnum(string $enumClassName, bool|float|int|string|null $databaseValue): mixed;


    public function convertEnumToDatabaseValue(mixed $enum): bool|float|int|string|null;
}
