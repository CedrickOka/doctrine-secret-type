# Getting Started With Doctrine Secret Type

This bundle help the user input high quality data into your web services REST.

## Prerequisites

The OkaInputHandlerBundle has the following requirements:

 - PHP 7.4+
 - Symfony 4.4+

## Installation

Installation is a quick (I promise!) 3 step process:

1. Download project
2. Generate RSA keys
3. Configure doctrine and enjoy!

### Step 1: Download the project

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require coka/doctrine-secret-type
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Generate the SSH keys

```bash
$ mkdir -p config/cert
$ openssl genpkey -out config/cert/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/cert/private.pem -out config/cert/public.pem -pubout
```

### Step 3: Configuration

To configure Doctrine to use secret as a field type, you'll need to set up
the following in your bootstrap:

```php
<?php
// in bootstrapping code

// ...

use Doctrine\DBAL\Types\Type;

// ...

// Register my type
Type::addType('secret', 'Oka\Doctrine\Types\SecretType');

/** @var \Oka\Doctrine\Types\SecretType $secretType */
$secretType = Type::getType('secret');
$secretType->setPrivateKeyPath($privateKeyPath)
			->setPublicKeyPath($publicKeyPath)
			->setPassPhrase($passphrase);
```

The type has to be registered with the database platform as well:

```php
<?php
$conn = $em->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('LONGTEXT', 'secret');
```

In Symfony 3 Version :

```yaml
# app/config/config.yml
doctrine:
    dbal:
        types:
            secret: 'Oka\Doctrine\Types\SecretType'
        mapping_types:
            secret: text
```

In Symfony 4 Version :

```yaml
# config/packages/oka_doctrine_secret_type.yaml
doctrine:
    dbal:
        types:
            secret: 'Oka\Doctrine\Types\SecretType'
        mapping_types:
            secret: LONGTEXT
```

## Usage
 
Then, in your models, you may annotate properties by setting the `@Column`
type to `secret`. Doctrine will handle the rest.
 
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

If you use the XML Mapping instead of PHP annotations :

```XML
<field name="value" type="secret" />
```

You can also use the YAML Mapping :

```yaml
    value:
      type: secret
```
