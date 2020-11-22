# Integrador Swoole
🧱 Projeto criado com  [Siler](https://github.com/leocavalcante/siler), que facilita a utilização do Swoole.


## Comandos

### `composer start`
Levanta o [Servidor embutido do PHP](https://www.php.net/manual/en/features.commandline.webserver.php) na porta 8080.

### `composer swoole`
Levanta o [Servidor do Swoole](https://www.swoole.co.uk/) na porta 9501.

#### `docker-compose up`
Levanta o  [Docker](https://www.docker.com/) usando [Dwoole](https://github.com/leocavalcante/dwoole) na porta 9501.

## Testes

Esta usando [`ramsey/devtools`](https://github.com/ramsey/devtools) com o [Pest](https://pestphp.com/).

```bash
composer test:unit
```

## Configuração de injeção de dependência

### `bootstrap.php`
Foi usando o componente de injeção de dependência do Symfony o [The DependencyInjection Component](https://symfony.com/doc/current/components/dependency_injection.html)


## Configuração de variáveis de ambiente

### `etc/app.ini`
```ini
[app]
name = "Hello, World!"

[service.one]
url = 'http://127.0.0.1:8000'

[service.two]
url = 'http://127.0.0.1:8001'

[service.three]
url = 'http://127.0.0.1:8003'

```

## Rotas
Arquivo de configuração.
### `index.php`

#
#### Busca uma informação segura por ID do [Service One](service_one/README.md)

**URL** : `/v1/find-information-by-id/{informationId}`

**Method** : `GET`

**Response**

```json
{
  "cpf": "00012398727",
  "nome": "Name test",
  "endereco": "Address test"
}
```

#### Serviço esta usando Mock dos dados.
#### Busca uma informação segura por CPF do [Service One](service_one/README.md) e  [Service Two](service_one/README.md)

**URL** : `/v1/find-information-by-cpf-service-one-two/{cpf}`

**Method** : `GET`

**Response**
```json
{
  "cliente": {
    "cpf": "74894733064",
    "nome": "Nome mock test",
    "endereco": "Endereço Mock"
  },
  "detalhes": {
    "idade": 30,
    "bens": {
      "veiculos": {
        "carro": "hb20"
      },
      "imoveis": {
        "apto": "rua xx ali"
      }
    },
    "renda": [
      "empresa x",
      "aposentadoria"
    ]
  }
}
```
#### Serviço esta usando Mock dos dados.
#### Busca informação de todos os serviços por CPF.

**URL** : `/v1/find-information-all-by-cpf/{cpf}`

**Method** : `GET`

**Response**
```json
{
  "cliente": {
    "cpf": "74894733064",
    "nome": "Nome mock test",
    "endereco": "Endereço Mock"
  },
  "detalhes": {
    "idade": 30,
    "bens": {
      "veiculos": {
        "carro": "hb20"
      },
      "imoveis": {
        "apto": "rua xx ali"
      }
    },
    "renda": [
      "empresa x",
      "aposentadoria"
    ]
  },
  "atividades": {
    "ultimaConsulta": "2020-08-05T00:00:00+00:00",
    "ultimaCompra": "kabum",
    "movimentacoesFinanceiras": {
      "debitos": [
        "deb -> 1",
        "deb -> 2"
      ],
      "cretidos": [
        "cred -> 1",
        "cred -> 2"
      ]
    }
  }
}
```
