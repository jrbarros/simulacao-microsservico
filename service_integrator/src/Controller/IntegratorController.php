<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\IntegratorService;
use Siler\Http\Response;

/**
 * Class IntegratorController
 */
class IntegratorController
{
    private IntegratorService $integratorService;

    /**
     * IntegratorController constructor.
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(IntegratorService $integratorService)
    {
        $this->integratorService = $integratorService;
    }

    /**
     * @param string $informationId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findInformationById(string $informationId): void
    {
        $userInfo = $this->integratorService->getInformationServiceOne($informationId);

        if ($userInfo === null) {
            Response\json(['message' => 'serviço indisponível'], 400);

            return;
        }

        $userInfo = $this->integratorService->respondeOnlyServiceOneResponse($userInfo);
        Response\json($userInfo);
    }

    /**
     * @param string $cpf
     */
    public function findInformationAllByCpf(string $cpf): void
    {
        $userInfo = $this->integratorService->getAllInformation($cpf);

        Response\json($userInfo);
    }

    /**
     * @param string $cpf
     */
    public function findInformationByCpfServiceOneAndTwo(string $cpf): void
    {
        $userInfo = $this->integratorService->getInformationServiceOneAndTwo($cpf);

        Response\json($userInfo);
    }
}
