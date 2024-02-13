<?php declare(strict_types = 1);

namespace BrandEmbassy\Doctrine\EnumType\Bridges\MyCLabsEnum;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @final
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;


    public function __construct(
        /**
         * @ORM\Column(type="string")
         */
        private string $name,
        /**
         * @ORM\Column(type="enumGender")
         */
        private Gender $gender
    ) {
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getGender(): Gender
    {
        return $this->gender;
    }


    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }
}
