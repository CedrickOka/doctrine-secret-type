<?php
namespace Oka\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

/**
 * 
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 * 
 */
class SecretType extends TextType
{
    use SecretTypeTrait;
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if (true === empty($value)) {
			return null;
		}
		
		return parent::convertToDatabaseValue($this->encrypt($value), $platform);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\TextType::convertToPHPValue()
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if (true === empty($value)) {
			return null;
		}
		
		return $this->decrypt(parent::convertToPHPValue($value, $platform));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\TextType::getName()
	 */
	public function getName()
	{
		return 'secret';
	}
}
