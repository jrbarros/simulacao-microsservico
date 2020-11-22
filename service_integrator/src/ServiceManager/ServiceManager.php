<?php declare(strict_types=1);


namespace App\ServiceManager;


interface ServiceManager
{
    public function getInformationSensitiveByCpf(string $cpf);
}
