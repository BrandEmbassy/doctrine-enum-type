<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use MabeEnum\Enum;
use function assert;
use function filter_var;
use function is_array;
use function is_subclass_of;
use const FILTER_VALIDATE_BOOLEAN;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;
use const PHP_VERSION_ID;

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

        // In PHP 7.4, the $databaseValue is always a string, but in 8.1, it might be int / float, etc..
        if (PHP_VERSION_ID < 80000) {
            if ($enumClassName::has($databaseValue)) {
                return $enumClassName::get($databaseValue);
            }

            if ($databaseValue === '' && $enumClassName::has(null)) {
                return $enumClassName::get(null);
            }

            $databaseValueAsFloat = filter_var($databaseValue, FILTER_VALIDATE_FLOAT);
            if ($databaseValueAsFloat !== false && $enumClassName::has($databaseValueAsFloat)) {
                return $enumClassName::get($databaseValueAsFloat);
            }

            $databaseValueAsInt = filter_var($databaseValue, FILTER_VALIDATE_INT);
            if ($databaseValueAsInt !== false && $enumClassName::has($databaseValueAsInt)) {
                return $enumClassName::get($databaseValueAsInt);
            }

            return $enumClassName::get(filter_var($databaseValue, FILTER_VALIDATE_BOOLEAN));
        }

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
