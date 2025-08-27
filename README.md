
---

# 📚 API Victus

API desenvolvida em **PHP (Laravel 12)** com **JWT Authentication** para gerir bibliotecas e vídeos online.
Este backend serve como provedor de dados para um frontend (Vue.js ou outro cliente).

---

## 🚀 Tecnologias Usadas

* [Laravel 12](https://laravel.com) (Framework PHP)
* [PHP 8.2+](https://www.php.net/)
* [MySQL](https://www.mysql.com/) (Banco de dados)
* [JWT Auth](https://jwt-auth.readthedocs.io/) (Autenticação)
* [Laravel Reverb](https://laravel.com/docs/reverb) (Broadcasting em tempo real)
* Queue e Cache com **Database Driver**

---

## ⚙️ Requisitos

* PHP ^8.2
* Composer ^2
* MySQL ^8 ou compatível
* Node.js & NPM (para desenvolvimento integrado com Vite, se necessário)

---

## 📥 Instalação

```bash
# 1. Clonar o projeto
git clone https://github.com/joasumbo/victus_api.git
cd seu-repo

# 2. Instalar dependências
composer install

# 3. Copiar o arquivo de configuração
cp .env.example .env

# 4. Gerar a chave da aplicação
php artisan key:generate

# 5. Configurar o banco de dados no .env
DB_DATABASE=app_video
DB_USERNAME=root
DB_PASSWORD=

# 6. Rodar as migrations
php artisan migrate

# 7. Gerar a chave secreta do JWT
php artisan jwt:secret

# 8. Storage link
php artisan storage:link

# 9. Iniciar o servidor local
php artisan serve
```

---

## 🔑 Autenticação (JWT)

A API usa **JWT**.
Para obter um token válido:

1. Registrar usuário: `POST /api/register`
2. Login: `POST /api/login` → retorna o `token`
3. Usar o token no header das requisições protegidas:

```http
Authorization: Bearer {token}
```

---

## 📡 Endpoints

### 🔐 Autenticação

* `POST /api/register` → Criar novo usuário
* `POST /api/login` → Fazer login
* `POST /api/logout` → Logout (token inválido)
* `GET /api/me` → Dados do usuário autenticado

### 📚 Bibliotecas

* `GET /api/libraries` → Listar bibliotecas
* `POST /api/libraries` → Criar nova biblioteca
* `GET /api/libraries/{id}` → Ver detalhes da biblioteca
* `DELETE /api/libraries/{id}` → Apagar biblioteca

### 🎬 Vídeos

* `POST /api/videos` → Upload de novo vídeo
* `GET /api/videos/{id}` → Detalhes / reprodução
* `DELETE /api/videos/{id}` → Apagar vídeo

---

## 🗄️ Estrutura de Pastas (simplificada)

```
app/
 ├── Models/
 │   ├── User.php
 │   ├── Library.php
 │   └── Video.php
 ├── Http/Controllers/
 │   ├── AuthController.php
 │   ├── LibraryController.php
 │   └── VideoController.php
routes/
 ├── api.php
 └── web.php
```

---

## 🛠️ Scripts Úteis

Rodar testes:

```bash
composer test
```

Rodar servidor + filas + Vite (desenvolvimento):

```bash
composer run dev
```

---

## 📌 Observações

* Uploads de vídeos estão configurados para o **filesystem local** (`storage/app`).
* Pode-se configurar armazenamento externo (S3, DigitalOcean Spaces, etc.) editando o `.env`.
* Broadcasting está habilitado com **Laravel Reverb** (porta padrão `8080`).

---
