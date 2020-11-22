<?php

declare(strict_types=1);

namespace App\ServiceManager;

use App\UserInformation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

use function Co\run;
use function GuzzleHttp\json_decode;
use function array_key_exists;
use function go;

/**
 * Class ServiceOneManager
 */
class ServiceOneManager implements ServiceManager
{
    private Client $client;

    /**
     * ServiceOneManager constructor.
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $informationId
     *
     * @return UserInformation|null
     *
     * @throws GuzzleException
     */
    public function getInformationSensitiveById(string $informationId): ?UserInformation
    {
        $response = $this->client->request('GET', '/v1/sensitive-information/' . $informationId);

        return $this->processRequest($response);
    }

    /**
     * @param string $informationId
     *
     * @return UserInformation|null
     *
     * @throws GuzzleException
     */
    public function getInformationSensitiveByCpf(string $informationId): ?UserInformation
    {
        $response = $this->client->request('GET', '/v1/sensitive-information/' . $informationId);

        return $this->processRequest($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return UserInformation|null
     */
    public function processRequest(ResponseInterface $response): ?UserInformation
    {
        if ($response->getStatusCode() === 200) {
            $result = json_decode($response->getBody()->getContents(), true);

            if (! array_key_exists('data', $result)) {
                $this->sendErrorRequest($response);

                return null;
            }

            return $this->buildInformation($result['data']);
        }

        return null;
    }

    /**
     * @param ResponseInterface $response
     */
    private function sendErrorRequest(ResponseInterface $response): void
    {
        echo "envia log de erro do service 1\n";
        run(function () {
            go(function () {
                echo "envia log de erro do service 1\n";
            });
        });
    }

    /**
     * @param $data
     *
     * @return UserInformation
     */
    private function buildInformation(array $data): UserInformation
    {
        return new UserInformation($data['cpf'], $data['name'], $data['address']);
    }
}
