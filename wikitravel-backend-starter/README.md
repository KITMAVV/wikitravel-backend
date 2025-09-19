# WikiTravel Backend Starter (Laravel)

Готовий каркас бекенду (Laravel + Sanctum + MySQL).
Кроки встановлення (у папці `src`):
1) `composer require laravel/sanctum` (якщо ще не поставлено)
2) `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
3) Заповни `.env` (DB_*), `APP_URL=http://wikitravel-backend.test`
4) `php artisan key:generate`
5) `php artisan migrate`
6) `php artisan storage:link`

Основні роуты дивись у `routes/api.php`.
