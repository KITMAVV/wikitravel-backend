# Готовий каркас бекенду (Laravel + Sanctum + MySQL(Sqlite для dev))

## Кроки встановлення (у папці `src`)
1. Встанови пакет Sanctum *(якщо ще не поставлено)*:
   ```bash
   composer require laravel/sanctum
   ```
   

2. Опублікуй конфіг/міграції Sanctum:
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. Заповни `.env`, ось приклад для dev:
   ```env
   APP_NAME=WikiTravel
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000

   LOG_CHANNEL=stack
   LOG_LEVEL=debug

   # Наприклад sqlite
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite

   SESSION_DRIVER=cookie
   SESSION_LIFETIME=120
   SESSION_DOMAIN=127.0.0.1
   SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
   ```

4. **(DB_*)** — створи файлик датабази в `/database` *(наприклад sqlite)*.

5. Виконай команди:
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   ```

---

**Основні роуты** дивись у `routes/api.php`.
