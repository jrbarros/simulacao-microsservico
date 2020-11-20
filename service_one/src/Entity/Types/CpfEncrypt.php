<?php declare(strict_types=1);


namespace App\Entity\Types;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Exception;

/**
 * Class CpfEncrypt
 * @package App\Entity\Types
 */
class CpfEncrypt extends Type
{

    private const ENCRYPTED = 'cpfEncrypted';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'TEXT COMMENT \'(CpfEncrypted)\'';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ENCRYPTED;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : string
    {
        $key = hash('sha256', $_ENV['ENCRYPTION_CUSTOM_KEY']);
        $iv  = substr(hash('sha256', $_ENV['ENCRYPTION_CUSTOM_IV_KEY']), 0, 16);

        return openssl_decrypt(base64_decode($value), $_ENV['ENCRYPTION_METHOD'], $key, 0, $iv);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     * @throws Exception
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $key = hash('sha256', $_ENV['ENCRYPTION_CUSTOM_KEY']);

        /**
         * Dessa o iv não fica random e posso usar para buscar diretas
         */
        $iv = substr(hash('sha256', $_ENV['ENCRYPTION_CUSTOM_IV_KEY']), 0, 16);

        return base64_encode(openssl_encrypt($value,  $_ENV['ENCRYPTION_METHOD'], $key, 0, $iv));
    }

}
