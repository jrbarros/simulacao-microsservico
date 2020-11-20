<?php

declare(strict_types=1);

namespace App\Controller;

use App\Validator\SensitiveInformationExceptionMessage;

/**
 * Trait ValidateEmptyContentTrait.
 */
trait ValidateEmptyContentTrait
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function isEmpty(string $content): bool
    {
        if (empty($content)) {
            throw new \RuntimeException(SensitiveInformationExceptionMessage::EMPTY_BODY);
        }

        return false;
    }
}
