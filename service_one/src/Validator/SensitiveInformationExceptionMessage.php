<?php declare(strict_types=1);


namespace App\Validator;


class SensitiveInformationExceptionMessage
{
    public const EMPTY_BODY = 'Body do request esta vazio!';
    public const DEFAULT_ERROR_MESSAGE = 'Erro no processamento de informações do cliente';
    public const ADDRESS_NOT_FOUND_OR_BLANK = "Campo 'address' não enviado ou estar em branco";
    public const CPF_NOT_VALID = "Campo 'cpf' não é valido";
    public const CPF_NOT_FOUND_OR_BLANK = "Campo 'cpf' não enviado ou estar em branco";
    public const NAME_NOT_FOUND_OR_BLANK = "Campo 'name' não enviado ou estar em branco";
    public const SENSITIVE_INFORMATION_EXIST = 'CPF já gravado na base, utilize o recurso de update';
}
