<?php

declare(strict_types=1);

namespace App\ServiceManager;

use GuzzleHttp\Client;

/**
 * Class ServiceThreeManager
 * @package App\ServiceManager
 */
class ServiceThreeManager implements ServiceManager
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

    public function getInformationSensitiveByCpf(string $cpf): array
    {
        return [
            'ultimaConsulta' => '2020-08-05T00:00:00+00:00',
            'ultimaCompra' => 'kabum',
            'movimentacoesFinanceiras' => [
                'debitos' => [
                    'deb -> 1',
                    'deb -> 2',
                ],
                'cretidos' => [
                    'cred -> 1',
                    'cred -> 2'
                ]
            ]
        ];
    }
}
