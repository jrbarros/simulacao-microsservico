<?php

declare(strict_types=1);

namespace App\ServiceManager;

use GuzzleHttp\Client;

class ServiceTwoManager implements ServiceManager
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
     * @param string $cpf
     * @return array
     */
    public function getInformationSensitiveByCpf(string $cpf): array
    {
        return [
            'idade' => 30,
            'bens' => [
                'veiculos' => [
                    'carro' => 'hb20'
                ],
                'imoveis' => [
                    'apto' => 'rua xx ali'
                ]
            ],
            'renda' => [
                'empresa x',
                'aposentadoria'
            ]
        ];
    }
}
