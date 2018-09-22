[![CircleCI](https://circleci.com/gh/BrandEmbassy/doctrine-enum-type/tree/master.svg?style=svg)](https://circleci.com/gh/BrandEmbassy/doctrine-enum-type/tree/master)
[![Total Downloads](https://poser.pugx.org/BrandEmbassy/doctrine-enum-type/downloads)](https://packagist.org/packages/brandembassy/doctrine-enum-type)
[![Latest Stable Version](https://poser.pugx.org/BrandEmbassy/doctrine-enum-type/v/stable)](https://github.com/BrandEmbassy/doctrine-enum-type/releases)

# Doctrine Enum Type

This extension brings easy usage of enums in your Doctrine entities.


## Why to use enums

* You can type-check passed value
* You document what values are valid
* You can add useful methods to your enum class


## Installation

```
composer require brandembassy/doctrine-enum-type
```


## Choosing enum library

Currently three enum libraries are supported out of the box:

* [marc-mabe/php-enum](https://github.com/marc-mabe/php-enum)
* [consistence/consistence](https://github.com/consistence/consistence)
* [myclabs/php-enum](https://github.com/myclabs/php-enum)

Don't worry, you can use another Enum implementation, just create bridge implementing `BrandEmbassy\Doctrine\EnumType\EnumImplementation` interface.


## Usage

### Setup Doctrine

Let's say we have this enum with colors:

```php
class Color extends Enum
{

    public const BLACK = 'black';
    public const WHITE = 'white';
    public const RED = 'read';
    public const GREEN = 'green';
    public const BLUE = 'blue';

}
```

Now we need to tell Doctrine about our enum class. You can use `BrandEmbassy\Doctrine\EnumType\EnumTypesManager` for it:

```php
use BrandEmbassy\Doctrine\EnumType\Bridges\MarcMabeEnum\MarcMabeEnumBridge;
use BrandEmbassy\Doctrine\EnumType\EnumTypesManager;

$enumTypesManager = new EnumTypesManager(new MarcMabeEnumBridge());
$enumTypesManager->addEnumTypeDefinition('enumColor', Color::class);

$enumTypesManager->initializeEnumTypes();
```

This initialization must be done before working with entities or schema that is using your enums.

First parameter of `EnumTypesManager` is object implementing `BrandEmbassy\Doctrine\EnumType\EnumImplementation` that describes enum implementation you are using.

Method `addEnumTypeDefinition` accepts the name of your enum that you want to use in your entities, second one is your enum class. 


### Start using enums in your entities

We are ready to go, we can now define our car entity using color enum:

```php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="car")
 */
final class Car
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $brand;

    /**
     * @ORM\Column(type="enumColor")
     * @var Color
     */
    private $color;

    public function __construct(string $brand, Color $color)
    {
        $this->brand = $brand;
        $this->color = $color;
    }

    ...

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(Color $color): void
    {
        $this->color = $color;
    }

}

$user = new User('Skoda', Color::get(Color::GREEN));
```

## Internals

Database columns generated by this library are of type `VARCHAR(255)`. This brings one constraint to your enums: value of your enum can't be longer than 255 characters. 
