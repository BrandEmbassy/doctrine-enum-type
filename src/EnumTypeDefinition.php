<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

final class EnumTypeDefinition
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $className;

    public function __construct(string $name, string $className)
    {
        $this->name = $name;
        $this->className = $className;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

}
