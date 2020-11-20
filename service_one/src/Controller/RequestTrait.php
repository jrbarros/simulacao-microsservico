<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SensitiveInformation;

trait RequestTrait
{
    /**
     * @param string $content
     *
     * @return array
     */
    public function contentToArray(string $content): array
    {
        try {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException('Erro ao processar o conteúdo do request para array');
        }
    }

    public function buildSensitiveInformationDataReturn(SensitiveInformation  $sensitiveInformation): array
    {
        return [
            'cpf' => $sensitiveInformation->getCpf(),
            'name' => $sensitiveInformation->getName(),
            'address' => $sensitiveInformation->getAddress()
        ];
    }
}
