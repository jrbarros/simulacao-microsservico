<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SensitiveInformation;
use App\Repository\SensitiveInformationRepository;
use App\Validator\CpfValidator;
use App\Validator\SensitiveInformationExceptionMessage;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * Class SensitiveInformationService.
 */
class SensitiveInformationService
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * @var SensitiveInformationRepository
     */
    private SensitiveInformationRepository $sensitiveInformationRepository;

    /**
     * SensitiveInformationService constructor.
     *
     * @param ManagerRegistry                $managerRegistry
     * @param SensitiveInformationRepository $sensitiveInformationRepository
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        SensitiveInformationRepository $sensitiveInformationRepository
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->sensitiveInformationRepository = $sensitiveInformationRepository;
    }

    /**
     * @param array $data
     */
    public function processSave(array $data): void
    {
        $this->validateRequestInformation($data);

        if ($this->findSensitiveInformation($data['cpf']) instanceof SensitiveInformation) {
            throw new RuntimeException(SensitiveInformationExceptionMessage::SENSITIVE_INFORMATION_EXIST);
        }

        $sensitiveInformation = $this->buildSensitiveInformation($data);

        $this->save($sensitiveInformation);
    }

    /**
     * @param $data
     *
     * @return void|RuntimeException
     */
    public function validateRequestInformation($data): void
    {
        if (!array_key_exists('cpf', $data) || empty($data['cpf'])) {
            throw new RuntimeException(SensitiveInformationExceptionMessage::CPF_NOT_FOUND_OR_BLANK);
        }

        if (!array_key_exists('name', $data) || empty($data['name'])) {
            throw new RuntimeException(SensitiveInformationExceptionMessage::NAME_NOT_FOUND_OR_BLANK);
        }

        if (!array_key_exists('address', $data) || empty($data['address'])) {
            throw new RuntimeException(SensitiveInformationExceptionMessage::ADDRESS_NOT_FOUND_OR_BLANK);
        }

        if (false === CpfValidator::validateCpf($data['cpf'])) {
            throw new \RuntimeException(SensitiveInformationExceptionMessage::CPF_NOT_VALID);
        }
    }

    /**
     * @param SensitiveInformation $sensitiveInformation
     */
    public function save(SensitiveInformation $sensitiveInformation): void
    {
        $this->managerRegistry->getManager()->persist($sensitiveInformation);
        $this->managerRegistry->getManager()->flush();
    }

    /**
     * @param array $data
     *
     * @return SensitiveInformation
     */
    private function buildSensitiveInformation(array $data): SensitiveInformation
    {
        $sensitiveInformation = new SensitiveInformation();

        $sensitiveInformation->setCpf($data['cpf']);
        $sensitiveInformation->setName($data['name']);
        $sensitiveInformation->setAddress($data['address']);

        return $sensitiveInformation;
    }

    private function findSensitiveInformation(string $cpf): ?SensitiveInformation
    {
        return $this->sensitiveInformationRepository->findOneBy(['cpf' => $cpf]);
    }
}
