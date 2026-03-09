# Repair Service PHP (Laravel)

Тестовое веб-приложение: "Заявки в ремонтную службу".

Стек: `PHP 8.4 + Laravel + SQLite + Docker Compose`.

## Функционал

Роли:
- `dispatcher` (диспетчер)
- `master` (мастер)

Сущность заявки (`repair_requests`):
- `client_name` (обязательно)
- `phone` (обязательно)
- `address` (обязательно)
- `problem_text` (обязательно)
- `status`: `new | assigned | in_progress | done | canceled`
- `assigned_to` (мастер, nullable)
- `created_at`, `updated_at`

Экраны:
1. Создание заявки: `/requests/create`
2. Панель диспетчера: `/dispatcher`
3. Панель мастера: `/master`

Авторизация упрощенная: выбор пользователя на странице `/login`.

## Запуск (Docker Compose)

```bash
docker compose up --build
```

Приложение будет доступно: `http://localhost:18080`.

При старте контейнер автоматически:
- создаёт `.env` (если нет),
- создаёт `database/database.sqlite` (если нет),
- выполняет миграции,
- выполняет сиды,
- запускает встроенный сервер Laravel.

## Тестовые пользователи (из сидов)

- Диспетчер: `dispatcher@example.com` (имя: `Диспетчер Анна`)
- Мастер 1: `master1@example.com` (имя: `Мастер Иван`)
- Мастер 2: `master2@example.com` (имя: `Мастер Петр`)

Пароль в сидах: `password` (но для текущей реализации вход через выбор пользователя).

## Проверка защиты от гонки

Сценарий: два параллельных запроса на действие "Взять в работу" для одной и той же заявки.

Ожидаемое поведение:
- один запрос успешен (`200`),
- второй получает конфликт (`409`) с сообщением, что заявка уже взята.

### Вариант через PowerShell-скрипт

```powershell
./race_test.ps1
```

Скрипт:
- логинится как мастер,
- отправляет два параллельных POST-запроса на `master.take` для заявки в статусе `assigned`.

## Автотесты

В проекте есть минимум 2 автотеста (`PHPUnit`):
- `tests/Feature/RequestCreationTest.php` — проверка создания заявки со статусом `new`.
- `tests/Feature/MasterTakeRaceTest.php` — проверка, что повторный `take` возвращает `409`.

Запуск тестов:

```bash
docker compose run --rm app php artisan test
```

## Структура основных файлов

- Роуты: `routes/web.php`
- Контроллеры:
  - `app/Http/Controllers/PublicRequestController.php`
  - `app/Http/Controllers/DispatcherController.php`
  - `app/Http/Controllers/MasterController.php`
  - `app/Http/Controllers/AuthChoiceController.php`
- Middleware ролей: `app/Http/Middleware/RoleMiddleware.php`
- Модели:
  - `app/Models/RepairRequest.php`
  - `app/Models/User.php`
- Миграции:
  - `database/migrations/0001_01_01_000000_create_users_table.php`
  - `database/migrations/2026_03_08_151500_create_repair_requests_table.php`
- Сиды: `database/seeders/DatabaseSeeder.php`

## Что сдать

- Ссылка на репозиторий
- Скриншоты 3 страниц:
  1. Создание заявки
  2. Панель диспетчера
  3. Панель мастера
- (Опционально) ссылка на деплой
