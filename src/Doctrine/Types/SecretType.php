<?php
namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

/**
 * 
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 * 
 */
class SecretType extends TextType
{
	/**
	 * @var string $privateKeyPath
	 */
	private $privateKeyPath;
	
	/**
	 * @var string $publicKeyPath
	 */
	private $publicKeyPath;
	
	/**
	 * @var string $passPhrase
	 */
	private $passPhrase;
	
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
	
	public function setPrivateKeyPath(string $privateKeyPath)
	{
		$this->privateKeyPath = $privateKeyPath;
		return $this;
	}
	
	public function setPublicKeyPath(string $publicKeyPath)
	{
		$this->publicKeyPath = $publicKeyPath;
		return $this;
	}
	
	public function setPassPhrase(string $passPhrase)
	{
		$this->passPhrase = $passPhrase;
		return $this;
	}
	
	private function encrypt($data) :?string
	{
		$fp = fopen($this->publicKeyPath, 'r');
		$publicKey = fread($fp, filesize($this->publicKeyPath));
		fclose($fp);
		
		$crypted = null;
		openssl_public_encrypt($data, $crypted, openssl_get_publickey($publicKey));
		
		return base64_encode($crypted);
	}
	
	private function decrypt($data) :?string
	{
		$fp = fopen($this->privateKeyPath, 'r');
		$privateKey = fread($fp, filesize($this->privateKeyPath));
		fclose($fp);
		
		$decrypted = null;
		openssl_private_decrypt(base64_decode($data), $decrypted, openssl_get_privatekey($privateKey, $this->passPhrase));
		
		return $decrypted;
	}
}
