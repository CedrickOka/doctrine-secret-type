<?php
namespace Oka\Doctrine\Types;

use Doctrine\DBAL\Types\ConversionException;

/**
 * 
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 * 
 */
trait SecretTypeTrait
{
    /**
     * @var string
     */
    private $privateKeyPath;
    
    /**
     * @var string
     */
    private $publicKeyPath;
    
    /**
     * @var string
     */
    private $passPhrase;
	
    public function setPrivateKeyPath(string $privateKeyPath) :self
    {
        $this->privateKeyPath = $privateKeyPath;
        return $this;
    }
    
    public function setPublicKeyPath(string $publicKeyPath) :self
    {
        $this->publicKeyPath = $publicKeyPath;
        return $this;
    }
    
    public function setPassPhrase(string $passPhrase) :self
    {
        $this->passPhrase = $passPhrase;
        return $this;
    }
    
    protected function encrypt($data) :?string
    {
        $output = '';
        $encrypted = null;
        $maxlength = 256;
        
        $fp = fopen($this->publicKeyPath, 'r');
        $publicKey = fread($fp, filesize($this->publicKeyPath));
        fclose($fp);
        
        $key = openssl_get_publickey($publicKey);
        
        while ($data) {
            if (false === openssl_public_encrypt(substr($data, 0, $maxlength), $encrypted, $key)) {
                throw ConversionException::conversionFailedSerialization($data, $this->getName(), openssl_error_string());
            }
            
            $output .= $encrypted;
            $data = substr($data, $maxlength);
        }
        
        return base64_encode($output);
    }
    
    protected function decrypt($data) :?string
    {
        $output = '';
        $decrypted = null;
        $maxlength = 512;
        
        $fp = fopen($this->privateKeyPath, 'r');
        $privateKey = fread($fp, filesize($this->privateKeyPath));
        fclose($fp);
        
        $data = base64_decode($data);
        $key = openssl_get_privatekey($privateKey, $this->passPhrase);
        
        while ($data) {
            if (false === openssl_private_decrypt(substr($data, 0, $maxlength), $decrypted, $key)) {
                throw ConversionException::conversionFailedUnserialization($data, $this->getName(), openssl_error_string());
            }
            
            $output .= $decrypted;
            $data = substr($data, $maxlength);
        }
        
        return $output;
    }
}
