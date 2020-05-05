<?php
namespace Oka\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

/**
 * 
 * @author Cedrick Oka Baidai <cedric.baidai@veone.net>
 * 
 */
class JsonSecretType extends JsonType
{
	use SecretTypeTrait;
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\JsonType::getSQLDeclaration()
	 */
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if (true === empty($value)) {
			return '';
		}
		
		return $this->encrypt(parent::convertToDatabaseValue($value, $platform));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\TextType::convertToPHPValue()
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if ('' === $value) {
			return null;
		}
		
		return parent::convertToPHPValue($this->decrypt($value), $platform);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\TextType::getName()
	 */
	public function getName()
	{
		return 'json_secret';
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Doctrine\DBAL\Types\JsonType::requiresSQLCommentHint()
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform)
	{
		return true;
	}
}
