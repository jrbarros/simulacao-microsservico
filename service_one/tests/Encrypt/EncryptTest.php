<?php declare(strict_types=1);


namespace App\Tests\Encrypt;


use App\DataFixtures\SensitiveInformationFixtures;
use App\Encrypt\Encrypt;
use App\Entity\SensitiveInformation;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EncryptTest extends WebTestCase
{

    use FixturesTrait;

    protected KernelBrowser $client;
    private ? Registry $doctrine;
    private ?Encrypt $encrypt;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $container =  self::$container;

        $this->doctrine = $container->get('doctrine');
        $this->encrypt = $container->get(Encrypt::class);

    }

    public function test_verify_encrypt_data()
    {
        $this->loadFixtures([SensitiveInformationFixtures::class]);

        $sensitive = $this->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf' => SensitiveInformationFixtures::CPF]);

        $cpf = "'". $this->encrypt->encryptCpf(SensitiveInformationFixtures::CPF) . "'";
        $array = $this->doctrine
            ->getConnection()
            ->query("select * from sensitive_information where cpf = {$cpf} limit 1")
            ->fetchAssociative();

        self::assertNotEmpty($array);
        self::assertNotEquals($array['cpf'], $sensitive->getCpf());
        self::assertNotEquals($array['name'], $sensitive->getName());
        self::assertNotEquals($array['address'], $sensitive->getAddress());
    }
}
