<?php declare(strict_types=1);


namespace App\Tests\Service;



use App\DataFixtures\SensitiveInformationFixtures;
use App\Entity\SensitiveInformation;
use App\Helpers\Generator;
use App\Service\SensitiveInformationService;
use App\Tests\DataBaseManagerTest;
use App\Validator\SensitiveInformationExceptionMessage;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use hollodotme\FastCGI\Encoders\NameValuePairEncoder;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use ReflectionClass;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class SensitiveInformationServiceTest
 * @package App\Tests\Service
 */
class SensitiveInformationServiceTest extends WebTestCase
{
    use FixturesTrait;

    private ?SensitiveInformationService $service;

    private $client;

    private ?Registry $doctrine;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container =  self::$container;

        /** @var SensitiveInformationService|null $service */
        $this->service = $container->get(SensitiveInformationService::class);
        $this->doctrine = $container->get('doctrine');
    }

    public function test_function_validateDataInformation_return_exception_CPF_NOT_FOUND_OR_BLANK(): void
    {
        $data = [];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::CPF_NOT_FOUND_OR_BLANK);
        $this->service->validateRequestInformation($data);

        $data = ['cpf' => ''];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::CPF_NOT_FOUND_OR_BLANK);
        $this->service->validateRequestInformation($data);
    }

    public function test_function_validateDataInformation_return_exception_NAME_NOT_FOUND_OR_BLANK(): void
    {
        $data = [
            'cpf' => Generator::cpf(),
            'name' => ''
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::NAME_NOT_FOUND_OR_BLANK);
        $this->service->validateRequestInformation($data);

        $data = [
            'cpf' => Generator::cpf()
        ];

        $this->service->validateRequestInformation($data);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::NAME_NOT_FOUND_OR_BLANK);
    }

    public function test_function_validateDataInformation_return_exception_ADDRESS_NOT_FOUND_OR_BLANK(): void
    {

        $data = [
            'cpf' => Generator::cpf(),
            'name' => 'Name test',
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::ADDRESS_NOT_FOUND_OR_BLANK);
        $this->service->validateRequestInformation($data);

        $data = [
            'cpf' => Generator::cpf(),
            'name' => 'Name test',
            'address' => ''
        ];

        $this->service->validateRequestInformation($data);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::ADDRESS_NOT_FOUND_OR_BLANK);
    }

    public function test_function_validateDataInformation_return_exception_CPF_NOT_VALID(): void
    {


        $data = [
            'cpf' => '00000000000',
            'name' => 'Name test',
            'address' => 'Address test'
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(SensitiveInformationExceptionMessage::CPF_NOT_VALID);
        $this->service->validateRequestInformation($data);
    }

    public function test_function_buildSensitiveInformation_return_correct(): void
    {
        $data = [
            'cpf' => '00000000000',
            'name' => 'Name test',
            'address' => 'Address test'
        ];

        $sensitiveInformation = $this->invokeMethod($this->service, 'buildSensitiveInformation',[$data]);

        self::assertInstanceOf(SensitiveInformation::class, $sensitiveInformation);
    }

    public function test_function_buildSensitiveInformation_return_notice(): void
    {
        $data = [
            'cpf' => '00000000000',
            'name' => 'Name test',
        ];

        $this->expectNotice();
        $this->invokeMethod($this->service, 'buildSensitiveInformation',[$data]);
    }

    public function test_function_findSensitiveInformation_return_ok(): void
    {
        $this->loadFixtures([SensitiveInformationFixtures::class]);

        $sensitiveDB = $this->invokeMethod($this->service, 'findSensitiveInformation', [SensitiveInformationFixtures::CPF]);
        self::assertInstanceOf(SensitiveInformation::class, $sensitiveDB);
    }

    public function test_function_findSensitiveInformation_return_null(): void
    {
        $this->loadFixtures([SensitiveInformationFixtures::class]);

        $sensitiveDB = $this->invokeMethod($this->service, 'findSensitiveInformation', ['00000000000']);
        self::assertNull($sensitiveDB);
    }

    public function test_function_save_return_ok(): void
    {
        $this->loadFixtures([SensitiveInformationFixtures::class]);

        $sensitiveInformation = new SensitiveInformation();

        $sensitiveInformation->setCpf(SensitiveInformationFixtures::CPF);
        $sensitiveInformation->setName('Nome de teste');
        $sensitiveInformation->setAddress('Endereço de teste');

        $sensitiveDB = $this->invokeMethod($this->service, 'save', [$sensitiveInformation]);

        self::assertNull($sensitiveDB);
    }



    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
