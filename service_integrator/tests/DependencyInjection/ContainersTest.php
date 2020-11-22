<?php

declare(strict_types=1);

namespace App\Test\DependencyInjection;

use App\Controller\IntegratorController;
use App\Service\IntegratorService;
use App\ServiceManager\ServiceOneManager;
use App\ServiceManager\ServiceThreeManager;
use App\ServiceManager\ServiceTwoManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function PHPUnit\Framework\assertInstanceOf;
use function it;

it(
    'check container configuration instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';

        assertInstanceOf(ContainerBuilder::class, $container);
    },
);

it(
    'check integrator controller instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';
        $integratorController = $container->get('integrator.controller');

        assertInstanceOf(IntegratorController::class, $integratorController);
    },
);

it(
    'check integrator service instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';
        $integratorController = $container->get('integrator.service');

        assertInstanceOf(IntegratorService::class, $integratorController);
    },
);

it(
    'check service ONE manager instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';
        $integratorController = $container->get('service.one.manager');

        assertInstanceOf(ServiceOneManager::class, $integratorController);
    },
);

it(
    'check service TWO manager instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';
        $integratorController = $container->get('service.two.manager');

        assertInstanceOf(ServiceTwoManager::class, $integratorController);
    },
);

it(
    'check service THREE manager instanced',
    function () {
        $container = require __DIR__ . '/../../bootstrap.php';
        $integratorController = $container->get('service.three.manager');

        assertInstanceOf(ServiceThreeManager::class, $integratorController);
    },
);
