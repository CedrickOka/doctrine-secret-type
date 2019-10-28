# Doctrine Secret Type

Custom Doctrine type that encrypted column value with openssl.

[![Latest Stable Version](https://poser.pugx.org/coka/coka/doctrine-secret-type/v/stable)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![Total Downloads](https://poser.pugx.org/coka/coka/doctrine-secret-type/downloads)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![Latest Unstable Version](https://poser.pugx.org/coka/coka/doctrine-secret-type/v/unstable)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![License](https://poser.pugx.org/coka/coka/doctrine-secret-type/license)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![Monthly Downloads](https://poser.pugx.org/coka/coka/doctrine-secret-type/d/monthly)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![Daily Downloads](https://poser.pugx.org/coka/coka/doctrine-secret-type/d/daily)](https://packagist.org/packages/coka/coka/doctrine-secret-type)
[![Travis CI](https://travis-ci.org/CedrickOka/coka/doctrine-secret-type.svg?branch=master)](https://travis-ci.org/CedrickOka/coka/doctrine-secret-type)

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require coka/cors-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Usage

This package provides a App\Doctrine\Types\SecretType class that extends Doctrine\DBAL\Types\TextType.
To get this working, you have to register the concrete column types.

```php
<?php
// in bootstrapping code

// ...

use Doctrine\DBAL\Types\Type;

// ...

// Register my type
Type::addType('secret', 'App\Doctrine\Types\SecretType');
```

The type has to be registered with the database platform as well:

```php
<?php
$conn = $em->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('LONGTEXT', 'secret');
```

 You can use type now:
 
```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="api_key")
 */
class Apikey
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int int
     */
    protected $id;
    
    /**
     * @ORM\Column(type="secret")
     * @var string $value
     */
    protected $value;

    // Getters and setters...
}
```
