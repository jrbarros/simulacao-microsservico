<?php

declare(strict_types=1);

namespace App\Test\Feature;

use App\ServiceManager\ServiceOneManager;
use App\UserInformation;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use function expect;
use function test;

test(
    'teste serviceOne processRequest success',
    function () {
        $mock = new MockHandler([
            new Response(200, [], '{"message":"Informa\u00e7\u00e3o obtidas com sucesso!",
            "data":{"cpf":"00012398727","name":"Name test","address":"Address test"}}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(
            [
                'base_uri' => 'local.fake.com.br',
                'exceptions' => false,
                'handler' => $handlerStack,
            ],
        );
        $serviceOne = new ServiceOneManager($client);

        $response = $client->request('GET', '/');

        /** @var UserInformation $user */
        $user = $serviceOne->processRequest($response);

        expect($user)->toBeInstanceOf(UserInformation::class);
        expect($user->getAddress())->toEqual('Address test');
    },
);

test(
    'teste serviceOne processRequest error 400',
    function () {
        $mock = new MockHandler([
            new Response(400, [], '{"message":"Erro no processamento de informações do cliente"}}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(
            [
                'base_uri' => 'local.fake.com.br',
                'exceptions' => false,
                'handler' => $handlerStack,
            ],
        );
        $serviceOne = new ServiceOneManager($client);

        $response = $client->request('GET', '/');

        /** @var UserInformation $user */
        $user = $serviceOne->processRequest($response);

        expect($user)->toBeNull();
    },
);

test(
    'teste serviceOne processRequest Response body error',
    function () {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"message":"Informa\u00e7\u00e3o obtidas com sucesso!",
                "dataset":{"cpf":"00012398727","name":"Name test","address":"Address test"}}',
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(
            [
                'base_uri' => 'local.fake.com.br',
                'exceptions' => false,
                'handler' => $handlerStack,
            ],
        );

        $serviceOne = new ServiceOneManager($client);

        $response = $client->request('GET', '/');

        /** @var null $user */
        $user = $serviceOne->processRequest($response);
        expect($user)->toBeNull();
        $this->expectOutputString("envia log de erro do service 1\n");
    },
);
