<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CpfValidator
 * @package App\Validator
 */
class CpfValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (self::validateCpf($value)) {
           return;
        }
        /* @var $constraint Cpf */
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

    /**
     * @param string $cpf
     * @return bool
     */
    public static function validateCpf(string $cpf): bool
    {
        $cpf = preg_replace("/\D/", '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (self::verifySequenceCPF($cpf) === false) {
            return false;
        }

        if (self::validateDigitsCPF($cpf) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param $cpf
     * @return bool
     */
    private static function verifySequenceCPF(string $cpf): bool
    {
        return !(
            $cpf === '00000000000' ||
            $cpf === '11111111111' ||
            $cpf === '22222222222' ||
            $cpf === '33333333333' ||
            $cpf === '44444444444' ||
            $cpf === '55555555555' ||
            $cpf === '66666666666' ||
            $cpf === '77777777777' ||
            $cpf === '88888888888' ||
            $cpf === '99999999999'
        );
    }

    /**
     * @param string $cpf
     * @return bool
     */
    private static function validateDigitsCPF(string $cpf): bool
    {
        for ($i = 9; $i < 11; $i++) {
            for ($d = 0, $c = 0; $c < $i; $c++) {
                $d += $cpf[$c] * (($i + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] !== (string) $d) {
                return false;
            }
        }

        return true;
    }
}
