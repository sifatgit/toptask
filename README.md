# Toptask

A simple Laravel task management app that demonstrates:

- Auth-scoped data access (projects/tasks are tied to the authenticated user)
- Authorization using Policies (`ProjectPolicy`, `TaskPolicy`)
- Route Model Binding (`{project}`, `{task}`)
- Transaction-safe priority swapping when updating a task

## Requirements

- PHP 8.3+
- Composer
- Node.js + npm
- A database (MySQL)

## Setup (Local)

1) Install dependencies

```bash
composer install
```

2) Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your database credentials in `.env`.

3) Run migrations

```bash
php artisan migrate
```

4) Run the app

```bash
php artisan serve
npm run dev
```

## Notes

- The UI is intentionally minimal; the focus is backend structure and data integrity.
- Task priorities are treated as unique per project. When updating a task to an already-used priority, the app swaps priorities inside a database transaction to keep data consistent.
