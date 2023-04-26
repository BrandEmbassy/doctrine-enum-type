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
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Gender
     *
     * @ORM\Column(type="enumGender")
     */
    private $gender;


    public function __construct(string $name, Gender $gender)
    {
        $this->name = $name;
        $this->gender = $gender;
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
