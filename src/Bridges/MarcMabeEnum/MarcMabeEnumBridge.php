<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use MabeEnum\Enum;
use function assert;
use function is_array;
use function is_subclass_of;

/**
 * @final
 */
class MarcMabeEnumBridge implements EnumImplementation
{
    public function getBaseEnumClassName(): string
    {
        return Enum::class;
    }


    /**
     * @param bool|float|int|string|null $databaseValue
     *
     * @return mixed enum
     */
    public function convertDatabaseValueToEnum(string $enumClassName, $databaseValue)
    {
        assert(is_subclass_of($enumClassName, Enum::class));

        return $enumClassName::get($databaseValue);
    }


    /**
     * @param mixed $enum
     */
    public function convertEnumToDatabaseValue($enum): string
    {
        assert($enum instanceof Enum);

        $value = $enum->getValue();
        assert(!is_array($value), 'Array is not supported as enum value when storing it to database');

        return (string)$value;
    }
}
