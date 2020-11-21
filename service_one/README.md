Service One
=================

Microsserviço responsável por processar e obter os dados criptografados.

Instalação
----------

```bash
composer install
```

Configuração
------------
Alterar o .env do projeto ou copiar para um .env.local
```bash
DATABASE_URL=''

###< gracious/doctrine-encryption-bundle ###
ENABLE_ENCRYPTION=true
ENCRYPTION_KEY=''

### encrypt/encrypt ###
ENCRYPTION_CUSTOM_KEY=''
ENCRYPTION_CUSTOM_IV_KEY=''
ENCRYPTION_METHOD=''

```
#
Para gerar o ENCRYPTION_KEY executar o seguinte trecho de código.
```bash
php -r 'echo sodium_bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));'
```
#
Para gerar as ENCRYPTION_CUSTOM_KEY and ENCRYPTION_CUSTOM_IV_KEY pode ser criado qualquer 
cadeia de string. No ENCRYPTION_METHOD foi utilizado o 'AES-256-CBC', pois, utilizei o openssl para criar
uma criptografia de duas vias.
```bash

ENCRYPTION_CUSTOM_KEY='89e4adf7-7fea-47b8-a279-492761b3f824'
ENCRYPTION_CUSTOM_IV_KEY='44542777-d7a6-48ac-978f-20a19744c960'
ENCRYPTION_METHOD='AES-256-CBC'
```
#

Cache
-----
Foi utilizado no desenvolvimento o array cache adapter, em produção é altamente recomentado que utilize o
redis, mencache e etc.

```yaml
// cache.yaml
framework:
    cache:
        app: cache.adapter.array
```


Testes
------

```bash
composer test
```

#
Rotas
-----
Para obter uma lista
```bash
bin/console debug:router
```

#
Criar uma informação segura

**URL** : `/v1/sensitive-information`

**Method** : `POST`

**Content**

[**Gerar CPF**](https://www.4devs.com.br/gerador_de_cpf)
```json
{
  "cpf": "CPF VALIDO",
  "name": "Name test",
  "address": "Address test" 
}
```

#
Atualizar uma informação segura

**URL** : `/v1/sensitive-information/{id}`

**Method** : `PUT`

**Content**

```json
{
  "name": "Name test",
  "address": "Address test" 
}
```

#
Busca uma informação segura

**URL** : `/v1/sensitive-information/{id}`

**Method** : `GET`

**Response**

```json
{
  "cpf": "45909050001",
  "name": "Name test",
  "address": "Address test" 
}
```
