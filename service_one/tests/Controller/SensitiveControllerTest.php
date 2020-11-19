<?php declare(strict_types=1);


namespace App\Tests\Controller;


use App\DataFixtures\SensitiveInformationFixtures;
use App\Helpers\Generator;
use App\Validator\SensitiveInformationExceptionMessage;
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

    public function setUp(): void
    {
        $this->client = static::createClient();
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

        $erroMessage = "Campo 'address' não enviado ou estar em branco";
        $messageResponse = 'Erro no processamento de informações do cliente';

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals($erroMessage, $responseBody['error']);
        self::assertEquals($messageResponse, $responseBody['message']);
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
}
