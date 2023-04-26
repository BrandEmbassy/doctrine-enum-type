<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use MyCLabs\Enum\Enum;
use function assert;
use function is_array;
use function is_subclass_of;

/**
 * @final
 */
class MyCLabsEnumBridge implements EnumImplementation
{
    public function getBaseEnumClassName(): string
    {
        return Enum::class;
    }


    /**
     * @return mixed enum
     */
    public function convertDatabaseValueToEnum(string $enumClassName, string $databaseValue)
    {
        assert(is_subclass_of($enumClassName, Enum::class));

        return new $enumClassName($databaseValue);
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
