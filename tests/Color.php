<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use MabeEnum\Enum;

/**
 * @final
 */
class Color extends Enum
{
    public const BLACK = 'black';
    public const WHITE = 'white';
    public const RED = 'read';
    public const GREEN = 'green';
    public const BLUE = 'blue';
}
