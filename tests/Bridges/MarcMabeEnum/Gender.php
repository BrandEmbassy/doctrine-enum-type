<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum;

use MabeEnum\Enum;

/**
 * @final
 */
class Gender extends Enum
{
    public const MALE = 'male';
    public const FEMALE = 'female';
}
