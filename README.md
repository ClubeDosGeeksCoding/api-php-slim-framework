# api-php-slim-framework
API RESTful com PHP Slim Framework - adaptado para Slim v3.*

## Instalando - para Slim v3.*
```shell
git clone https://github.com/andre-msantos/api-php-slim-framework.git
```

## Instalando - para Slim v2.*
```shell
git clone https://github.com/ClubeDosGeeksCoding/api-php-slim-framework.git
```

Instale as dependencias com composer
```shell
composer install
```

## Banco de dados
```sql
CREATE DATABASE api;
CREATE TABLE pessoa (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(30) NOT NULL,
	email VARCHAR(50),
	dt_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
)
```

## [Tutorial](http://clubedosgeeks.com.br/programacao/php/api-restful-com-php-e-slim-framework)
