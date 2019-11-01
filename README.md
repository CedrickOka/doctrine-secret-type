# Doctrine Secret Type

[![Latest Stable Version](https://poser.pugx.org/coka/doctrine-secret-type/v/stable)](https://packagist.org/packages/coka/doctrine-secret-type)
[![Total Downloads](https://poser.pugx.org/coka/doctrine-secret-type/downloads)](https://packagist.org/packages/coka/doctrine-secret-type)
[![Latest Unstable Version](https://poser.pugx.org/coka/doctrine-secret-type/v/unstable)](https://packagist.org/packages/coka/doctrine-secret-type)
[![License](https://poser.pugx.org/coka/doctrine-secret-type/license)](https://packagist.org/packages/coka/doctrine-secret-type)
[![Monthly Downloads](https://poser.pugx.org/coka/doctrine-secret-type/d/monthly)](https://packagist.org/packages/coka/doctrine-secret-type)
[![Daily Downloads](https://poser.pugx.org/coka/doctrine-secret-type/d/daily)](https://packagist.org/packages/coka/doctrine-secret-type)
[![composer.lock](https://poser.pugx.org/coka/doctrine-secret-type/composerlock)](https://packagist.org/packages/coka/doctrine-secret-type)
[![Travis CI](https://travis-ci.org/CedrickOka/doctrine-secret-type.svg?branch=master)](https://travis-ci.org/CedrickOka/doctrine-secret-type)

The coka/doctrine-secret-type package provides the ability to use custom Doctrine type that encrypted column value with openssl.

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require coka/doctrine-secret-type
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Generate the SSH keys 

```bash
$ mkdir -p config/cert
$ openssl genpkey -out config/cert/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/cert/private.pem -out config/cert/public.pem -pubout
```

### Configuration

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

/** @var \Oka\Doctrine\Types\SecretType $type */
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
            secret:  Oka\Doctrine\Types\SecretType
        mapping_types:
            LONGTEXT: secret
```

In Symfony 4 Version :

```yaml
# config/packages/oka_doctrine_secret_type.yaml
doctrine:
    dbal:
        types:
            secret:  Oka\Doctrine\Types\SecretType
        mapping_types:
            LONGTEXT: secret
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

## Copyright and License

The coka/doctrine-secret-type library is copyright © Baidai Cedrick Oka <https://github.com/CedrickOka> and licensed for use under the MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[source](https://github.com/CedrickOka/doctrine-secret-type)
[release](https://packagist.org/packages/coka/doctrine-secret-type)
[license](https://github.com/CedrickOka/doctrine-secret-type/blob/master/LICENSE)
[build](https://travis-ci.org/CedrickOka/doctrine-secret-type)
[downloads](https://packagist.org/packages/coka/doctrine-secret-type)
