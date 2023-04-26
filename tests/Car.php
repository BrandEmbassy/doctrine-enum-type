<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="car")
 *
 * @final
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $brand;

    /**
     * @ORM\Column(type="enumColor", nullable=true)
     */
    private ?Color $color;


    public function __construct(string $brand, ?Color $color = null)
    {
        $this->brand = $brand;
        $this->color = $color;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getBrand(): string
    {
        return $this->brand;
    }


    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }


    public function getColor(): ?Color
    {
        return $this->color;
    }


    public function setColor(?Color $color): void
    {
        $this->color = $color;
    }
}
