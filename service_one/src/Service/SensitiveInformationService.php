<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SensitiveInformation;
use App\Repository\SensitiveInformationRepository;
use App\Validator\CpfValidator;
use App\Validator\SensitiveInformationExceptionMessage;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Cache\Adapter\AdapterInterface;

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
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * SensitiveInformationService constructor.
     *
     * @param ManagerRegistry                $managerRegistry
     * @param SensitiveInformationRepository $sensitiveInformationRepository
     * @param AdapterInterface               $adapter
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        SensitiveInformationRepository $sensitiveInformationRepository,
        AdapterInterface $adapter
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->sensitiveInformationRepository = $sensitiveInformationRepository;
        $this->adapter = $adapter;
    }

    /**
     * @param array $data
     */
    public function processSave(array $data): void
    {
        $this->validateRequestInformation($data);

        if ($this->findSensitiveInformationByCpf($data['cpf']) instanceof SensitiveInformation) {
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
     *
     * @throws InvalidArgumentException
     */
    public function save(SensitiveInformation $sensitiveInformation): void
    {
        $this->managerRegistry->getManager()->persist($sensitiveInformation);
        $this->managerRegistry->getManager()->flush();

        $this->processCache($sensitiveInformation);
    }

    /**
     * @param array                     $data
     * @param SensitiveInformation|null $sensitiveInformation
     *
     * @return SensitiveInformation
     */
    public function buildSensitiveInformation(array $data, ?SensitiveInformation $sensitiveInformation = null): SensitiveInformation
    {
        if (null === $sensitiveInformation) {
            $sensitiveInformation = new SensitiveInformation();
        }

        $sensitiveInformation->setCpf($data['cpf']);
        $sensitiveInformation->setName($data['name']);
        $sensitiveInformation->setAddress($data['address']);

        return $sensitiveInformation;
    }

    /**
     * @param array                $data
     * @param SensitiveInformation $sensitiveInformation
     */
    public function processUpdate(array $data, SensitiveInformation $sensitiveInformation): void
    {
        /*
         * necessário para poder usar as funções de validação sem ter que fragmentar ainda mais.
         */
        $data['cpf'] = $sensitiveInformation->getCpf();

        $this->validateRequestInformation($data);

        $sensitiveInformation = $this->buildSensitiveInformation($data, $sensitiveInformation);

        $this->save($sensitiveInformation);
    }

    /**
     * @param string $cpf
     *
     * @return SensitiveInformation|null
     */
    public function findSensitiveInformationByCpf(string $cpf): ?SensitiveInformation
    {
        if ($this->adapter->hasItem($cpf)) {
            return $this->adapter->getItem($cpf)->get();
        }

        return $this->sensitiveInformationRepository->findOneBy(['cpf' => $cpf]);
    }

    /**
     * @param string $id
     *
     * @return SensitiveInformation|null
     *
     * @throws InvalidArgumentException
     */
    public function findSensitiveInformationById(string $id): ?SensitiveInformation
    {
        if ($this->adapter->hasItem($id)) {
            return $this->adapter->getItem($id)->get();
        }

        return $this->sensitiveInformationRepository->find($id);
    }

    /**
     * @param SensitiveInformation $sensitiveInformation
     *
     * @throws InvalidArgumentException
     */
    private function processCache(SensitiveInformation $sensitiveInformation): void
    {
        $item = $this->adapter->getItem($sensitiveInformation->getId());
        $item
            ->set($sensitiveInformation)
            ->expiresAfter(new \DateInterval('P30D'));

        $this->adapter->save($item);

        /**
         * Criando um segundo cache para busca por CPF
         */
        $item = $this->adapter->getItem($sensitiveInformation->getCpf());
        $item
            ->set($sensitiveInformation)
            ->expiresAfter(new \DateInterval('P30D'));

        $this->adapter->save($item);
    }
}
