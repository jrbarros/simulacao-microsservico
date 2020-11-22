<?php

declare(strict_types=1);

namespace App\Service;

use App\ServiceManager\ServiceOneManager;
use App\ServiceManager\ServiceThreeManager;
use App\ServiceManager\ServiceTwoManager;
use App\UserInformation;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Class IntegratorService
 * @package App\Service
 */
class IntegratorService
{
    private ServiceOneManager $serviceOneManager;
    private ServiceTwoManager $serviceTwoManager;
    private ServiceThreeManager $serviceThreeManager;

    /**
     * IntegratorService constructor.
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(
        ServiceOneManager $serviceOneManager,
        ServiceTwoManager $serviceTwoManager,
        ServiceThreeManager $serviceThreeManager
    ) {
        $this->serviceOneManager = $serviceOneManager;
        $this->serviceTwoManager = $serviceTwoManager;
        $this->serviceThreeManager = $serviceThreeManager;
    }

    /**
     * @param string $cpf
     *
     * @return array
     */
    public function getAllInformation(string $cpf): array
    {
        $userInfo   = $this->getInformationServiceOneByCpfMock($cpf);
        $moreDetail = $this->getInformationServiceTwoByCpf($cpf);
        $actives    = $this->getInformationServiceThreeByCpf($cpf);

        return [
            'cliente'     => $userInfo,
            'detalhes'   => $moreDetail,
            'atividades' => $actives
        ];
    }

    /**
     * @param string $informationId
     *
     * @return UserInformation|null
     *
     */
    public function getInformationServiceOne(string $informationId): ?UserInformation
    {
        return $this->serviceOneManager->getInformationSensitiveById($informationId);
    }

    /**
     * @param string $cpf
     *
     * @return UserInformation|null
     *
     * @throws GuzzleException
     */
    public function getInformationServiceOneByCpf(string $cpf): ?UserInformation
    {
        return $this->serviceOneManager->getInformationSensitiveById($cpf);
    }

    /**
     * @param $cpf
     *
     * @return array
     */
    public function getInformationServiceOneAndTwo($cpf): array
    {
        $userInfo   = $this->getInformationServiceOneByCpfMock($cpf);
        $moreDetail = $this->getInformationServiceTwoByCpf($cpf);

        return [
            'cliente' => $userInfo,
            'detalhes' => $moreDetail
        ];
    }


    /**
     * @param UserInformation $information
     * @return array
     */
    public function respondeOnlyServiceOneResponse(UserInformation $information): array
    {
        return [
            'cpf' => $information->getCpf(),
            'nome' => $information->getName(),
            'endereco' => $information->getAddress(),
        ];
    }

    /**
     * @param $cpf
     *
     * @return string[]
     */
    private function getInformationServiceOneByCpfMock($cpf): array
    {
        return [
            'cpf' => '74894733064',
            'nome' => 'Nome mock test',
            'endereco' => 'Endereço Mock',
        ];
    }

    /**
     * @param $cpf
     *
     * @return array
     */
    private function getInformationServiceThreeByCpf($cpf): array
    {
        return $this->serviceThreeManager->getInformationSensitiveByCpf($cpf);
    }

    /**
     * @param $cpf
     *
     * @return array
     */
    private function getInformationServiceTwoByCpf($cpf): array
    {
        return $this->serviceTwoManager->getInformationSensitiveByCpf($cpf);
    }
}
