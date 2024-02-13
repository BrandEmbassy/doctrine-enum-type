<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\Enum;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
