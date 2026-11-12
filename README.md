# Portal F1 — Época 2025

Aplicação web informativa sobre a temporada 2025 de Fórmula 1, desenvolvida em PHP (POO + MVC) com MySQLi.
Trabalho Prático #1 de Programação Web — ECGM, ESTG-UNIPVC, 2026/2027.

**Autores:** Afonso Neiva (33104) · André Freitas (33782) · Diogo Viana (34504)

## Funcionalidades

- CRUD de pilotos, construtores e circuitos
- Filtros por atributo e pesquisa com texto livre
- Favoritos de pilotos, construtores e circuitos, guardados por utilizador autenticado
- Criação de conta e autenticação com sessões: as listagens são públicas; utilizadores registados podem adicionar e editar; só administradores podem eliminar
- Proteção CSRF em todos os formulários, passwords guardadas com `password_hash()`
- Interface responsiva (Bootstrap 5)

## Instalação (XAMPP)

1. Copiar a pasta do projeto para `htdocs/Programa-oWeb-F1`.
2. Importar `scripts.sql/pwf1.sql` no phpMyAdmin (cria a base de dados `pwf1` com os dados iniciais).
3. Abrir <http://localhost/Programa-oWeb-F1/>.

**Login de administrador:** `admin@f1.pt` / `123456`

Se o MySQL não estiver na porta 3306 (ou tiver outra password), criar `app/config.local.php`:

```php
<?php
return ['DB_PORT' => 3307];
```

Este ficheiro não é enviado para o git, por isso cada elemento do grupo pode ter o seu.

## Estrutura

```
index.php                 Front controller: sessão, configuração, autoload, arranque do router
.htaccess                 Encaminha todos os URLs para index.php?url=...
app/
  config.php              URL base e credenciais da base de dados
  core/
    App.php               Router: /pilotos/update/5 -> PilotosController->update(5)
    Controller.php        Classe base dos controladores (views, validação, autenticação, CSRF)
    Model.php             Classe base dos models (getById, delete, select com filtros)
    Db.php                Acesso à base de dados com MySQLi e prepared statements
    helpers.php           Funções das views: e(), url(), csrf_field(), auth_user()
  controllers/            Um controlador por secção (Home, Pilotos, Construtores, Circuitos, Sobre, Auth)
  models/                 Um model por tabela (herdam de app\core\Model)
  views/
    layout/               Cabeçalho (navbar) e rodapé comuns a todas as páginas
    <secção>/             Views de cada secção (index = listagem, form = criar/editar)
assets/                   Bootstrap 5 (local), CSS e JS da aplicação
scripts.sql/pwf1.sql      Script de criação da base de dados
```

## Rotas

| URL | Descrição | Login |
|---|---|---|
| `/` | Página inicial: números da época, vitórias e curiosidades | |
| `/home/pesquisa?q=` | Pesquisa global em pilotos, equipas e circuitos | |
| `/pilotos`, `/construtores`, `/circuitos` | Listagens com filtros e pesquisa | |
| `/<secção>/create` | Adicionar registo | utilizador |
| `/<secção>/update/{id}` | Editar registo | utilizador |
| `/<secção>/delete/{id}` (POST) | Eliminar registo | admin |
| `/favoritos/toggle` (POST) | Adicionar/remover favorito | utilizador |
| `/sobre` | Sobre o projeto e autores | |
| `/auth/register` | Criar conta | |
| `/auth/login`, `/auth/logout` | Iniciar / terminar sessão | |
