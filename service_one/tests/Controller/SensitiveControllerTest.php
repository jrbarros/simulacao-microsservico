<?php declare(strict_types=1);


namespace App\Tests\Controller;


use App\DataFixtures\SensitiveInformationFixtures;
use App\Entity\SensitiveInformation;
use App\Helpers\Generator;
use App\Validator\SensitiveInformationExceptionMessage;
use App\Validator\SensitiveInformationMessage;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SensitiveControllerTest
 * @package App\Tests\Controller
 */
class SensitiveControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected KernelBrowser $client;
    private ?Registry $doctrine;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $container =  self::$container;
        $this->doctrine = $container->get('doctrine');
        $this->loadFixtures([SensitiveInformationFixtures::class]);
    }

    public function test_create_success_sensitive_in_CREATE_information(): void
    {
        $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "cpf": "'. Generator::cpf() . '",
                      "name": "Name test",
                      "address": "Address test" 
                    }'
        );

        self::assertResponseIsSuccessful();
    }

    public function test_response_error_400_not_found_address_in_CREATE_sensitive_information(): void
    {
            $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "cpf": "'. Generator::cpf() . '",
                      "name": "Name test"
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::ADDRESS_NOT_FOUND_OR_BLANK, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }


    public function test_response_error_400_not_found_name_in_CREATE_sensitive_information(): void
    {
        $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "cpf": "'. Generator::cpf() . '",
                      "address": "Address test"
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::NAME_NOT_FOUND_OR_BLANK, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }


    public function test_response_error_400_not_found_empty_body_in_CREATE_sensitive_information(): void
    {
        $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            ''
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::EMPTY_BODY, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_response_error_400_not_found_CPF_in_CREATE_sensitive_information(): void
    {
        $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "name": "Name test",
                      "address": "Address test" 
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::CPF_NOT_FOUND_OR_BLANK, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_response_error_400_not_VALID_CPF_in_CREATE_sensitive_information(): void
    {
        $this->client->request(
            'POST',
            '/v1/sensitive-information',
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "cpf": "00000000000",
                      "name": "Name test",
                      "address": "Address test" 
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::CPF_NOT_VALID, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_update_success_sensitive_information(): void
    {
        $sensitive = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        $dataToCheck = [
            'name' => $sensitive->getName(),
            'address' => $sensitive->getAddress(),
            'cpf' => $sensitive->getCpf()
        ];

        $this->client->request(
            'PUT',
            '/v1/sensitive-information/'. $sensitive->getId(),
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "name": "Name update",
                      "address": "Address update" 
                    }'
        );

        $sensitiveUpdated = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        self::assertNotEquals($sensitiveUpdated->getName(), $dataToCheck['name']);
        self::assertNotEquals($sensitiveUpdated->getAddress(), $dataToCheck['address']);
        self::assertEquals($sensitiveUpdated->getCpf(), $dataToCheck['cpf']);
        self::assertResponseIsSuccessful();
    }

    public function test_update_error_400_not_found_address_sensitive_information(): void
    {
        $sensitive = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        $this->client->request(
            'PUT',
            '/v1/sensitive-information/'. $sensitive->getId(),
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                      "name": "Name update"
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::ADDRESS_NOT_FOUND_OR_BLANK, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }


    public function test_update_error_400_not_found_name_sensitive_information(): void
    {
        $sensitive = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        $this->client->request(
            'PUT',
            '/v1/sensitive-information/'. $sensitive->getId(),
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            '{
                        "address": "Address update" 
                    }'
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::NAME_NOT_FOUND_OR_BLANK, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_update_error_400_not_empty_body_sensitive_information(): void
    {
        $sensitive = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        $this->client->request(
            'PUT',
            '/v1/sensitive-information/'. $sensitive->getId(),
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            ''
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::EMPTY_BODY, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_update_error_400_not_found_sensitive_information(): void
    {
        $this->client->request(
            'PUT',
            '/v1/sensitive-information/'. 00000000,
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ],
            ''
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::SENSITIVE_INFORMATION_NOT_EXIST, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }

    public function test_get_success_sensitive_information(): void
    {
        /** @var SensitiveInformation $sensitive */
        $sensitive = $this
            ->doctrine
            ->getManager()
            ->getRepository(SensitiveInformation::class)
            ->findOneBy(['cpf'=> SensitiveInformationFixtures::CPF]);

        $this->client->request(
            'GET',
            '/v1/sensitive-information/'. $sensitive->getId(),
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ]
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals(SensitiveInformationMessage::GET_RESPONSE, $responseBody['message']);

        self::assertArrayHasKey('data', $responseBody);
        self::assertArrayHasKey('cpf', $responseBody['data']);
        self::assertArrayHasKey('name', $responseBody['data']);
        self::assertArrayHasKey('address', $responseBody['data']);

        self::assertEquals($sensitive->getCpf(), $responseBody['data']['cpf']);
        self::assertEquals($sensitive->getName(), $responseBody['data']['name']);
        self::assertEquals($sensitive->getAddress(), $responseBody['data']['address']);

        self::assertResponseIsSuccessful();
    }

    public function test_get_not_found_sensitive_information(): void
    {
        $this->client->request(
            'GET',
            '/v1/sensitive-information/'. 00000000,
            [],
            [],
            [   'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . ''
            ]
        );

        $responseBody = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals(SensitiveInformationExceptionMessage::SENSITIVE_INFORMATION_NOT_EXIST, $responseBody['error']);
        self::assertEquals(SensitiveInformationExceptionMessage::DEFAULT_ERROR_MESSAGE, $responseBody['message']);
    }
}
