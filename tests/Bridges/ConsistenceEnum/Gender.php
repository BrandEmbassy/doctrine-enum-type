<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\ConsistenceEnum;

use Consistence\Enum\Enum;

final class Gender extends Enum
{

    public const MALE = 'male';
    public const FEMALE = 'female';

}
