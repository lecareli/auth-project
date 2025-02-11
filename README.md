# API de Autenticação

## Propósito do Projeto

Esta API foi desenvolvida para fornecer funcionalidades de autenticação e gerenciamento de usuários, incluindo:

- Login: Autenticação de usuários com token de acesso.

- Registro: Criação de novos usuários.

- Recuperação de senha: Envio de e-mail para redefinição de senha e atualização da senha.

- Logout: Revogação de tokens de acesso.

- Informações do usuário: Retorna os dados do usuário autenticado.

A API é construída com o framework Laravel (PHP) e utiliza tokens de acesso para autenticação.

## Tecnologias Utilizadas

- Linguagem de Programação: PHP

- Framework: Laravel

- Banco de Dados: SQLite

- Autenticação: Tokens de acesso com Laravel Sanctum

### Ferramentas de Desenvolvimento

- Composer (gerenciador de dependências)

#### Outras Dependências:

- illuminate/support (validação e manipulação de requests)

- illuminate/auth (autenticação)

- illuminate/hashing (hash de senhas)

## Instalação e Execução Local
### Pré-requisitos
- PHP (versão 8.0 ou superior)

- Composer

- SQLite

### Passo a Passo para Instalação

1. Clone o repositório:

```bash
git clone https://github.com/lecareli/auth-project.git
```

2. Navegue até o diretório do projeto:
```bash
cd auth-project
```

3. Instale as dependências:
```bash
composer install
```

4. Configure o arquivo `.env`:
   
- Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

- Configure as variáveis de ambiente, como `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.

5. Gere a chave da aplicação:

```bash
php artisan key:generate
```

6. Execute as migrações:

```bash
php artisan migrate
```

7. Inicie o servidor de desenvolvimento:

```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`.

## Exemplos de Requisição

