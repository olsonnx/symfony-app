# Ogłoszenia — aplikacja Symfony

Aplikacja webowa typu tablica ogłoszeń, napisana w **Symfony 7.1 / PHP 8.2+**.
Projekt studencki, powstał jako ćwiczenie z frameworka Symfony i dobrych praktyk (testy, statyczna analiza, coding standards).

## Funkcje

- Rejestracja i logowanie użytkowników
- Zarządzanie profilem oraz zmiana hasła
- CRUD ogłoszeń (`Notice`) ze statusami (`NoticeStatus`)
- Kategorie i tagi ogłoszeń
- Panel administracyjny do zarządzania użytkownikami
- Role użytkowników (`ROLE_USER`, `ROLE_ADMIN`) i autoryzacja przez Security Voters
- Paginacja (KnpPaginator), tłumaczenia (i18n)

## Wymagania

- PHP >= 8.2
- Composer
- MySQL 8 (lub inna baza — patrz `DATABASE_URL`)
- Symfony CLI (opcjonalnie, wygodne do uruchamiania serwera dev)

## Instalacja

```bash
# 1. Zainstaluj zależności
composer install

# 2. Skonfiguruj środowisko lokalne (patrz sekcja Konfiguracja)
cp .env .env.local
# następnie ustaw własne DATABASE_URL i APP_SECRET w .env.local

# 3. Utwórz bazę, uruchom migracje i załaduj dane testowe
php bin/console doctrine:database:create
composer init-app   # migrations:migrate + fixtures:load
```

Uruchomienie serwera deweloperskiego:

```bash
symfony server:start
# lub
php -S localhost:8000 -t public/
```

## Konfiguracja

Zmienne środowiskowe znajdują się w `.env` (wartości domyślne/placeholdery).
**Realne dane lokalne — hasła do bazy, `APP_SECRET` — trzymaj w `.env.local`**, który jest ignorowany przez git i nigdy nie trafia do repozytorium.

Kluczowe zmienne:

- `DATABASE_URL` — połączenie do bazy danych
- `APP_SECRET` — sekret aplikacji (wygeneruj własny, np. `php -r "echo bin2hex(random_bytes(16));"`)
- `APP_ENV` — `dev` / `prod` / `test`

## Konta testowe (fixtures)

Po załadowaniu fixtures dostępne są konta seedowe (tylko do dev):

| Rola  | E-mail              | Hasło       |
|-------|---------------------|-------------|
| User  | `user0@example.com` … `user9@example.com`   | `user1234`  |
| Admin | `admin0@example.com` … `admin2@example.com` | `admin1234` |

## Narzędzia jakości kodu

```bash
# Testy
php bin/phpunit

# Statyczna analiza
vendor/bin/phpstan analyse

# Coding standards
vendor/bin/php-cs-fixer fix
vendor/bin/phpcs

# Refaktoryzacja
vendor/bin/rector process
```

## Struktura

```
src/
├── Controller/      # kontrolery (Notice, Category, Tag, Admin, Security, ...)
├── Entity/          # encje Doctrine (User, Notice, Category, Tag, ...)
├── Repository/      # repozytoria Doctrine
├── Form/            # formularze i typy
├── Service/         # logika biznesowa
├── Security/        # Voters i logika autoryzacji
├── DataFixtures/    # dane testowe (Faker)
└── Dto/             # obiekty transferu danych
```

## Licencja

Projekt prywatny / edukacyjny.
