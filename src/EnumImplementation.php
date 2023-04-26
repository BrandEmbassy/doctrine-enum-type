<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

interface EnumImplementation
{
    public function getBaseEnumClassName(): string;


    /**
     * @return mixed enum
     */
    public function convertDatabaseValueToEnum(string $enumClassName, string $databaseValue);


    /**
     * @param mixed $enum
     */
    public function convertEnumToDatabaseValue($enum): string;
}
