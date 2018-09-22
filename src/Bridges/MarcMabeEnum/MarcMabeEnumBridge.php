<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use BrandEmbassy\Doctrine\EnumType\EnumImplementation;
use MabeEnum\Enum;

final class MarcMabeEnumBridge implements EnumImplementation
{

    public function getBaseEnumClassName(): string
    {
        return Enum::class;
    }

    /**
     * @param string $enumClassName
     * @param string $databaseValue
     * @return mixed enum
     */
    public function convertDatabaseValueToEnum(string $enumClassName, string $databaseValue)
    {
        \assert(\is_subclass_of($enumClassName, Enum::class));

        return $enumClassName::get($databaseValue);
    }

    /**
     * @param mixed $enum
     * @return string
     */
    public function convertEnumToDatabaseValue($enum): string
    {
        \assert($enum instanceof Enum);

        return (string)$enum->getValue();
    }

}
