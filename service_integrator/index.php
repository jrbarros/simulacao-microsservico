<?php declare(strict_types=1);

namespace App;

use App\Controller\IntegratorController;
use Siler\{Route, Http\Response};
use Symfony\Component\DependencyInjection\ContainerBuilder;

/** @var ContainerBuilder $container */
$container = require __DIR__ . '/bootstrap.php';

Route\get('/v1/find-information-by-id/{informationId}', function (array $routeParams) use ($container) {
    /** @var IntegratorController $integratorController */
    $integratorController = $container->get('integrator.controller');

    $integratorController->findInformationById($routeParams['informationId']);
});

Route\get('/v1/find-information-all-by-cpf/{cpf}', function (array $routeParams) use ($container) {
    /** @var IntegratorController $integratorController */
    $integratorController = $container->get('integrator.controller');

    $integratorController->findInformationAllByCpf($routeParams['cpf']);
});

Route\get('/v1/find-information-by-cpf-service-one-two/{cpf}', function (array $routeParams) use ($container) {
    /** @var IntegratorController $integratorController */
    $integratorController = $container->get('integrator.controller');

    $integratorController->findInformationByCpfServiceOneAndTwo($routeParams['cpf']);
});


if (!Route\did_match()) {
    Response\json('Not found', 404);
}
