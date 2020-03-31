<?php
namespace Oka\Doctrine\Types;

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
        $fp = fopen($this->publicKeyPath, 'r');
        $publicKey = fread($fp, filesize($this->publicKeyPath));
        fclose($fp);
        
        $crypted = null;
        openssl_public_encrypt($data, $crypted, openssl_get_publickey($publicKey));
        
        return base64_encode($crypted);
    }
    
    protected function decrypt($data) :?string
    {
        $fp = fopen($this->privateKeyPath, 'r');
        $privateKey = fread($fp, filesize($this->privateKeyPath));
        fclose($fp);
        
        $decrypted = null;
        openssl_private_decrypt(base64_decode($data), $decrypted, openssl_get_privatekey($privateKey, $this->passPhrase));
        
        return $decrypted;
    }
}
