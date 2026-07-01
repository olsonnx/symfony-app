# Ogłoszenia - Symfony Classifieds App

A classifieds / notice board web application built with **Symfony 7.1 / PHP 8.2+**.
A university project, created as an exercise in the Symfony framework and good practices (tests, static analysis, coding standards).

## Features

- User registration and login
- Profile management and password change
- Notice CRUD (`Notice`) with statuses (`NoticeStatus`)
- Notice categories and tags
- Admin panel for user management
- User roles (`ROLE_USER`, `ROLE_ADMIN`) with authorization via Security Voters
- Pagination (KnpPaginator) and translations (i18n)

## Requirements

- PHP >= 8.2
- Composer
- MySQL 8 (or another database — see `DATABASE_URL`)
- Symfony CLI (optional, convenient for running the dev server)

## Installation

```bash
# 1. Install dependencies
composer install

# 2. Configure your local environment (see Configuration)
cp .env .env.local
# then set your own DATABASE_URL and APP_SECRET in .env.local

# 3. Create the database, run migrations and load fixtures
php bin/console doctrine:database:create
composer init-app   # migrations:migrate + fixtures:load
```

Run the development server:

```bash
symfony server:start
# or
php -S localhost:8000 -t public/
```

## Configuration

Environment variables live in `.env` (default values / placeholders).
**Keep real local values — database passwords, `APP_SECRET` — in `.env.local`**, which is git-ignored and never committed.

Key variables:

- `DATABASE_URL` - database connection
- `APP_SECRET` - application secret (generate your own, e.g. `php -r "echo bin2hex(random_bytes(16));"`)
- `APP_ENV` - `dev` / `prod` / `test`

## Test accounts (fixtures)

After loading the fixtures, the following seed accounts are available (dev only):

| Role  | E-mail                                      | Password    |
|-------|---------------------------------------------|-------------|
| User  | `user0@example.com` … `user9@example.com`   | `user1234`  |
| Admin | `admin0@example.com` … `admin2@example.com` | `admin1234` |

## Code quality tools

```bash
# Tests
php bin/phpunit

# Static analysis
vendor/bin/phpstan analyse

# Coding standards
vendor/bin/php-cs-fixer fix
vendor/bin/phpcs

# Refactoring
vendor/bin/rector process
```

## Project structure

```
src/
├── Controller/      # controllers (Notice, Category, Tag, Admin, Security, ...)
├── Entity/          # Doctrine entities (User, Notice, Category, Tag, ...)
├── Repository/      # Doctrine repositories
├── Form/            # forms and types
├── Service/         # business logic
├── Security/        # Voters and authorization logic
├── DataFixtures/    # test data (Faker)
└── Dto/             # data transfer objects
```

## License

Private / educational project.
