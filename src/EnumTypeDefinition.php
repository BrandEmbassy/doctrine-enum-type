<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

/**
 * @final
 */
class EnumTypeDefinition
{
    private string $name;

    /**
     * @var class-string<object>
     */
    private string $className;


    /**
     * @param class-string<object> $className
     */
    public function __construct(string $name, string $className)
    {
        $this->name = $name;
        $this->className = $className;
    }


    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return class-string<object>
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
