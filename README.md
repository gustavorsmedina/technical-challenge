# Technical Challenge  🟢

#### ☕ Tecnologias utilizadas:

- Laravel 
- MySQL 
- RabbitMQ
- Pest

- Test Driven Development 
- Repository Pattern
- Service Layer
- Dependency Injection

- Pint / PSR-12

---

## ⚙️ Passo a passo

#### 1 - Clone o repositório

#### 2 - Copie o arquivo .env.example para .env

```cp .env.example .env```

#### 3 - Faça a instalação via Docker

```docker compose up -d```

#### 4 - Adicione as configurações do MySQL no arquivo .env

#### Pegue o IP do MySQL com: ```docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mysql```

```
DB_CONNECTION=mysql
DB_HOST= {IP Address}
DB_PORT=3306
DB_DATABASE=bank
DB_USERNAME=root
DB_PASSWORD=root
```

#### 5 - Adicione as configurações do RabbitMQ no arquivo .env

#### Pegue o IP do RabbitMQ com: ```docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' rabbitmq```

```
RABBITMQ_HOST= {IP Address}
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
```

#### 6 - Instale as dependências do projeto

```composer install```

#### 7 - Entre no container e altere a seguinte permissão:

```docker exec -it application bash```  
```chown -R www-data:www-data /var/www/storage```  
```exit```

#### 8 - Execute as migrations

```php artisan migrate```  
Marque (yes) para que ele crie o banco de dados e as tabelas

#### 9 - Agora você já pode disparar requisições no endereço:

```http://localhost/api```

#### 10 - Para consumir as mensagens do RabbitMQ

```php artisan rabbitmq:listener```

#### 11 - Para rodar os testes

```sudo chown -R $USER:www-data storage```   
```php artisan test```

---

## 📨 Requisições

### Todas requisições estão no arquivo: [Collection](./Technical%20Challenge.postman_collection.json)

---
### Usuários
| Método | Url             | Descrição               | Corpo da requisição       |
|--------|-----------------|-------------------------|---------------------------|
| POST   | /api/users      | Crie um novo usuário    | [JSON](#criarusuario)     |
| GET    | /api/users/{id} | Liste um usuário        |                           |
| GET    | /api/users      | Liste todos os usuários |                           |
| UPDATE | /api/users      | Atualize um usuário     | [JSON](#atualizarusuario) |
| DELETE | /api/users/{id} | Deleta um usuário       |                           |

### Carteiras
| Método | Url                 | Descrição                     | Corpo da requisição       |
|--------|---------------------|-------------------------------|---------------------------|
| POST   | /api/wallets        | Crie uma nova carteira        | [JSON](#criarcarteira)    |
| GET    | /api/wallets/{id}   | Liste uma carteira            |                           |
| POST   | /api/wallets/credit | Creditar saldo a uma carteira | [JSON](#creditarcarteira) |
| POST   | /api/wallets/debit  | Debitar saldo de uma carteira | [JSON](#debitarcarteira)  |

### Transações
| Método | Url                   | Descrição                | Corpo da requisição     |
|--------|-----------------------|--------------------------|-------------------------|
| POST   | /api/transactions     | Crie uma nova transação  | [JSON](#criartransacao) |
| GET    | /api/transactions/{id} | Liste uma transação      |                         |

---
## 📄 Corpo das requisições

### Usuários

##### <a id="criarusuario">[POST] /api/users - Crie um novo usuário.</a>

```json
{
    "full_name": "Gustavo Medina",
    "document": "12345678200",
    "email": "gustavo@account.com",
    "password": "secret",
    "user_type": "common"
}
```
##### <a id="atualizarusuario">[UPDATE] /api/users - Atualize um usuário.</a>

```json
{
    "full_name": "Novo Nome",
    "document": "00000000000",
    "email": "novoemail@account.com",
    "password": "novasenha",
    "user_type": "merchant"
}
```

### Carteiras

##### <a id="criarcarteira">[POST] /api/wallets - Crie uma nova carteira.</a>

```json
{
    "user_id": 1
}
```

##### <a id="creditarcarteira">[POST] /api/credit - Creditar saldo a uma carteira.</a>

```json
{
    "wallet_id": 1,
    "value": 10
}
```

##### <a id="debitarcarteira">[POST] /api/debit - Debitar saldo de uma carteira.</a>

```json
{
    "wallet_id": 1,
    "value": 10
}
```

### Transações

##### <a id="criartransacao">[POST] /api/transactions - Crie uma nova transação.</a>

```json
{
    "payer": 1,
    "payee": 2,
    "value": 5
}
```
