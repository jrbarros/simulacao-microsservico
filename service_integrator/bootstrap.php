<?php declare(strict_types=1);

namespace App;

use App\Controller\IntegratorController;
use App\Service\IntegratorService;
use App\ServiceManager\ServiceOneManager;
use App\ServiceManager\ServiceThreeManager;
use App\ServiceManager\ServiceTwoManager;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Siler\Config;
use Siler\Monolog as Log;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

require_once __DIR__ . '/vendor/autoload.php';

Config\load(__DIR__ . '/etc');
Log\handler(new StreamHandler('php://stdout'));

/**
 * Container de injeção de dependências.
 */
$containerBuilder = new ContainerBuilder();

/**
 * Configurando os service manager
 */
$containerBuilder->register('service.one.manager', ServiceOneManager::class)
    ->addArgument(new Client(
        [
            'base_uri' => (string) Config\config('service.one.url'),
            'exceptions' => false
        ])
    )
;

$containerBuilder->register('service.two.manager', ServiceTwoManager::class)
    ->addArgument(new Client(
        [
                'base_uri' => (string) Config\config('service.two.url'),
                'exceptions' => false
        ])
    )
;

$containerBuilder->register('service.three.manager', ServiceThreeManager::class)
    ->addArgument(new Client(
            [
                'base_uri' => (string) Config\config('service.three.url'),
                'exceptions' => false
            ])
    )
;

/**
 * Injetando os service manager no integrator service
 */
$containerBuilder->register('integrator.service', IntegratorService::class)
    ->addArgument(new Reference('service.one.manager'))
    ->addArgument(new Reference('service.two.manager'))
    ->addArgument(new Reference('service.three.manager'))
;

/**
 * Injetando o serviço no controller
 */
$containerBuilder->register('integrator.controller', IntegratorController::class)
    ->addArgument(new Reference('integrator.service'))
;

return $containerBuilder;
