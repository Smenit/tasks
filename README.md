## Требования
- PHP >= 7.4

## Установка
- Настроить .env файл на основе .env.example
- Настроить чтобы веб-сервер направлял все запросы на ```public/index.php```
- Выполнить команды:
```
composer install
php artisan key:generate
php artisan migrate
```