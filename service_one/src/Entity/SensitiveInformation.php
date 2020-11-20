<?php

namespace App\Entity;

use App\Repository\SensitiveInformationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensitiveInformationRepository::class)
 */
class SensitiveInformation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @ORM\Column(name="cpf", type="cpfEncrypted")
     *
     * @var string
     */
    private string $cpf;

    /**
     * @ORM\Column(name="name", type="encrypted")
     *
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(name="address", type="encrypted")
     *
     * @var string
     */
    private string $address;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf(string $cpf): void
    {
        $this->cpf = (string) preg_replace('/\D/', '', $cpf);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
