<?php declare(strict_types=1);


namespace App\Encrypt;


/**
 * Class Encrypt
 * @package App\Encrypt
 */
class Encrypt
{

    private string $encryptMethod;
    private string $secretKey;
    private string $secretIv;

    /**
     * Encrypt constructor.
     *
     * @param string $secretKey
     * @param string $secretIv
     * @param string $encryptMethod
     */
    public function __construct(string $secretKey, string $secretIv, string $encryptMethod)
    {
        $this->secretKey     = $secretKey;
        $this->secretIv      = $secretIv;
        $this->encryptMethod = $encryptMethod;
    }

    /**
     * @param string $value
     * @return string
     */
    public function decryptCpf(string $value): string
    {
        $key = hash('sha256', $this->secretKey);
        $iv  = substr(hash('sha256', $this->secretIv), 0, 16);

        return openssl_decrypt(base64_decode($value), $this->encryptMethod, $key, 0, $iv);
    }

    /**
     * @param string $value
     * @return string
     */
    public  function encryptCpf(string $value): string
    {
        $key = hash('sha256', $this->secretKey);

        /**
         * Dessa o iv não fica random e posso usar para buscar diretas
         */
        $iv = substr(hash('sha256', $this->secretIv), 0, 16);

        return base64_encode(openssl_encrypt($value, $this->encryptMethod, $key, 0, $iv));
    }
}
