<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 * @final
 */
class Gender extends Enum
{
    public const MALE = 'male';
    public const FEMALE = 'female';
}
