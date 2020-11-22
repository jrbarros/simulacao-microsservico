<?php

declare(strict_types=1);

namespace App;

/**
 * Class UserInformation
 */
class UserInformation
{
    private string $cpf;
    private string $name;
    private string $address;

    public function __construct(
        string $cpf,
        string $name,
        string $address
    ) {
        $this->cpf = $cpf;
        $this->name = $name;
        $this->address = $address;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
