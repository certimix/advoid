# Advoid — ERP para escritórios de advocacia

Sistema de gestão jurídica construído em **Laravel 13 + Livewire 3 + Tailwind CSS v4**.
Multi-escritório (multi-tenant via `escritorio_id`), com módulos de Processos,
Atendimentos e Agenda — e base de dados pronta para Financeiro, Publicações,
DNA de peças, Documentos e Equipes.

## Stack

- PHP 8.3+ · Laravel 13
- Livewire 3 (UI reativa, sem SPA)
- Tailwind CSS v4 (paleta definida em `resources/css/app.css`)
- Spatie Laravel Permission (perfis/permissões)
- MySQL 8 (ou SQLite para dev)

## Instalação

```bash
# 1. dependências PHP
composer install

# 2. ambiente
cp .env.example .env
php artisan key:generate

# 3. banco — configure .env (DB_DATABASE=advoid, etc) e rode
#    (Spatie) publica a migration de permissões antes do migrate:
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate --seed

# 4. front
npm install
npm run build        # ou: npm run dev
```

O seeder cria um escritório e um usuário admin:

- **E-mail:** admin@advoid.test
- **Senha:** password

> Troque essas credenciais antes de qualquer ambiente real.

## Módulos entregues

| Módulo | Rota | Descrição |
|---|---|---|
| Painel | `/dashboard` | Contadores e próximos compromissos |
| Processos | `/processos` | Lista com busca/filtros + ficha com abas (dados, partes, andamentos, prazos, financeiro) |
| Atendimentos | `/atendimentos` | Intake/CRM de casos, com conversão "virou processo" |
| Agenda | `/agenda` | Compromissos tipados (audiência, julgamento, perícia, reunião...) |

## Modelo de dados

Escritórios, usuários (com OAB/cargo), clientes, processos (+ partes, movimentações,
prazos, fases/kanban), compromissos, publicações, atendimentos, lançamentos
financeiros (+ categorias), documentos, modelos de peça (DNA jurídico) e equipes.

## Próximos módulos

Financeiro (telas), DNA de peças/editor, Publicações, Kanban de processos,
Clientes (CRUD dedicado), Equipes/Admin e camada de IA.

---

Projeto original — inspirado nas funcionalidades padrão do mercado jurídico,
com código e design próprios.
