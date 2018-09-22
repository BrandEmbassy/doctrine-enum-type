<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

interface EnumImplementation
{

    public function getBaseEnumClassName(): string;

    /**
     * @param string $enumClassName
     * @param string $databaseValue
     * @return mixed enum
     */
    public function convertDatabaseValueToEnum(string $enumClassName, string $databaseValue);

    /**
     * @param mixed $enum
     * @return string
     */
    public function convertEnumToDatabaseValue($enum): string;

}
